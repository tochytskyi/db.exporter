<?php

namespace app\components;

use app\models\Results;
use app\models\WpPosts;
use SimpleXMLElement;
use Yii;
use yii\base\Component;

class DbHelper extends Component
{

    /**
     * Clear DB
     */
    public static function clearDb()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        foreach ($tables as $table) {
            if ($table !== Results::tableName()) {
                Yii::$app->db->createCommand("DROP TABLE IF EXISTS {$table}")->execute();
            }
        }

        Yii::$app->cache->flush();
        Yii::$app->db->schema->refresh();
    }

    /**
     * Dump one sql file
     * @param $fileName
     * @param $dump
     */
    public static function executeDump($fileName, $dump)
    {
        self::clearDb();
        Yii::$app->db->createCommand(file_get_contents($fileName))->execute();

        $posts = [];
        $tables = Yii::$app->db->schema->getTableNames();
        foreach ($tables as $table) {
            if (strpos($table, '_posts') !== false) {
                $posts = (new \yii\db\Query())
                    ->select(['*'])
                    ->from($table)
                    ->all();
                break;
            }
        }

        $results = [];
        foreach ($posts as $post) {
            $results[] = [
                self::clearHtmlTags($post['post_title']),
                self::clearHtmlTags($post['post_content']),
                $dump
            ];
        }

        Yii::$app
            ->db
            ->createCommand()
            ->batchInsert(Results::tableName(), ['post_title', 'post_content', 'dump_name'], $results)
            ->execute();
    }

    /**
     * Clear text from HTML tags (img,a)
     * @param $text
     * @return string
     */
    public static function clearHtmlTags($text)
    {
        $text = preg_replace("/<img[^>]+\>/i", " ", $text);
        $text = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $text);
        return $text;
    }

    /**
     * CSV export
     * @param $rows
     */
    public static function exportCSV($rows)
    {
        $response = Yii::$app->getResponse();

        $list = [];
        foreach ($rows as $row) {
            $list[] = [
                $row->post_title,
                $row->post_content
            ];
        }

        $filename = 'export-' . date('Y-m-d_H:i:s') . '.csv';
        $file = \Yii::getAlias('@webroot') . '/export/' . $filename;
        $fp = fopen($file, 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        $response->sendFile($file);
    }

    /**
     * XML export
     * @param $rows
     */
    public static function exportXML($rows)
    {
        $response = Yii::$app->getResponse();

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><posts/>');

        foreach ($rows as $row) {
            $post = $xml->addChild('post');
            $post->addChild('title', $row->post_title);
            $post->addChild('content', $row->post_content);
        }

        $filename = 'export-' . date('Y-m-d_H:i:s') . '.xml';
        $file = \Yii::getAlias('@webroot') . '/export/' . $filename;
        file_put_contents($file, $xml->asXML());

        $response->sendFile($file);
    }

    /**
     * TXT export
     * @param $rows
     */
    public static function exportTXT($rows)
    {
        $response = Yii::$app->getResponse();

        $text = '';
        foreach ($rows as $row) {
            $text .= $row->post_title . ' | ' . $row->post_content . PHP_EOL;
        }

        $filename = 'export-' . date('Y-m-d_H:i:s') . '.txt';
        $file = \Yii::getAlias('@webroot') . '/export/' . $filename;
        file_put_contents($file, $text);

        $response->sendFile($file);
    }
}