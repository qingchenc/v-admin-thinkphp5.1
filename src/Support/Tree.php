<?php
// +----------------------------------------------------------------------
// | 封装树形结构
// +----------------------------------------------------------------------
//   Date: 2021-07-19
// +----------------------------------------------------------------------
// | Author: 清晨 <1849003043@qq.com>
// +----------------------------------------------------------------------

namespace QingChen\Admin\Support;

class Tree
{
    /**
     * 将数据按照层级，分层展示
     * |-
     *  |--
     * @param array $nodes
     * @param int $parentId
     * @param string $prefix
     * @param string $space
     * @return array
     */
    public static function buildSelectOptions(array $nodes = [], $parentId = 0, $prefix = '', $space = '&nbsp;')
    {
        $prefix  = $prefix ?: '┝'.$space;
        $options = [];
        foreach ($nodes as $index => $node) {
            if ($node['parent_id'] == $parentId) {
                $node['title']  = $prefix.$space.$node['title'];
                $childrenPrefix = str_replace('┝', str_repeat($space, 6), $prefix).'┝'.str_replace(['┝', $space], '', $prefix);
                $children       = self::buildSelectOptions($nodes, $node['id'], $childrenPrefix);
                $options[$node['id']] = $node['title'];
                if ($children) {
                    $options += $children;
                }
            }
        }
        return $options;
    }

    /**
     * 将数据按照父级数据进行展示
     *
     * @param $sourceData
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int $root
     * @return array
     */
    public static function generateTree($sourceData, $pk = 'id', $pid = 'parent_id', $child = 'child', $root = 0)
    {
        $tree     = [];
        $packData = [];

        foreach ($sourceData as $key => $data){
            $packData[$data[$pk]] = $data;
        }

        foreach ($packData as $key => $val) {
            if ($val[$pid] == $root) {
                //代表跟节点, 重点一
                $tree[] = &$packData[$key];
            } else {
                //找到其父类,重点二
                $packData[$val[$pid]][$child][] = &$packData[$key];
            }
        }
        return $tree;
    }

}
