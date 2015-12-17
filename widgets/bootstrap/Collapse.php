<?php

namespace app\widgets\bootstrap;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Collapse extends \yii\bootstrap\Collapse
{
    public function renderItem($header, $item, $index)
    {
        if (array_key_exists('content', $item)) {
            $id = $this->options['id'] . '-collapse' . $index;
            $options = ArrayHelper::getValue($item, 'contentOptions', []);
            $options['id'] = $id;
            $expanded = false;
            if (array_key_exists('expanded', $item)) {
                $expanded = $item['expanded'];
            }
            Html::addCssClass($options, 'panel-collapse collapse'. ($expanded ? ' in': ''));

            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            if ($encodeLabel) {
                $header = Html::encode($header);
            }
            $headerToggle = Html::a($header, '#' . $id, [
                    'class' => 'collapse-toggle',
                    'data-toggle' => 'collapse',
                    'data-parent' => '#' . $this->options['id'],
                    //'aria-expanded' => $expanded ? 'true' : ''
                ]) . "\n";

            $header = Html::tag('h4', $headerToggle, ['class' => 'panel-title']);

            if (is_string($item['content'])) {
                $content = Html::tag('div', $item['content'], ['class' => 'panel-body']) . "\n";
            } elseif (is_array($item['content'])) {
                $content = Html::ul($item['content'], [
                    'class' => 'list-group',
                    'itemOptions' => [
                        'class' => 'list-group-item'
                    ],
                    'encode' => false,
                ]) . "\n";
                if (isset($item['footer'])) {
                    $content .= Html::tag('div', $item['footer'], ['class' => 'panel-footer']) . "\n";
                }
            } else {
                throw new InvalidConfigException('The "content" option should be a string or array.');
            }
        } else {
            throw new InvalidConfigException('The "content" option is required.');
        }
        $group = [];

        $group[] = Html::tag('div', $header, ['class' => 'panel-heading']);
        $group[] = Html::tag('div', $content, $options);

        return implode("\n", $group);
    }
}
