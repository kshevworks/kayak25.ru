<?php
/**
 * Created by PhpStorm.
 * User: Shevyakhov_K
 * Date: 24.07.2018
 * Time: 17:06
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Menu extends AbstractHelper
{
    protected $items = [];

    protected $activeItemId = [];

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function setActiveItemId($activeItemId)
    {
        $this->activeItemId = $activeItemId;
    }

    public function render()
    {
        if (count($this->items) == 0)
            return ''; // Do nothing if there are no items.

        $result = '<nav class="navbar navbar-default navbar-fixed-top" role="navigation">';
        $result .= '<div class="navbar-header">';
        $result .= '<button type="button" class="navbar-toggle" ';
        $result .= 'data-toggle="collapse" data-target=".navbar-ex1-collapse">';
        $result .= '<span class="sr-only">Toggle navigation</span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '</button>';
        $result .= '<a class="navbar-brand page-scroll" href="/admin">Kayak25 Admin Panel</a>';
        $result .= '</div>';

        $result .= '<div class="collapse navbar-collapse navbar-ex1-collapse">';
        $result .= '<ul class="nav navbar-nav navbar-left">';

        // Визуализация элементов
        foreach ($this->items as $item) {
            $result .= $this->renderItem($item);
        }

        $result .= '</ul>';
        $result .= '<ul class="nav navbar-nav navbar-right" style="margin-right:50px;"><li><a href="/logout">Log out <i class="fa fa-sign-out"></i></a></li></ul>';
        $result .= '</div>';
        $result .= '</nav>';

        return $result;
    }

    protected function renderItem($item)
    {
        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id == $this->activeItemId);
        $label = isset($item['label']) ? $item['label'] : '';

        $result = '';

        if (isset($item['dropdown'])) {

            $dropdownItems = $item['dropdown'];

            $result .= '<li class="dropdown ' . ($isActive ? 'active' : '') . '">';
            $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            $result .= $label . ' <b class="caret"></b>';
            $result .= '</a>';

            $result .= '<ul class="dropdown-menu">';

            foreach ($dropdownItems as $item) {
                $link = isset($item['link']) ? $item['link'] : '#';
                $label = isset($item['label']) ? $item['label'] : '';

                $result .= '<li>';
                $result .= '<a href="' . $link . '">' . $label . '</a>';
                $result .= '</li>';
            }

            $result .= '</ul>';
            $result .= '</a>';
            $result .= '</li>';

        } else {
            $link = isset($item['link']) ? $item['link'] : '#';

            $result .= $isActive ? '<li class="active">' : '<li>';
            $result .= '<a href="' . $link . '">' . $label . '</a>';
            $result .= '</li>';
        }

        return $result;
    }

}