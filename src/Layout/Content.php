<?php

namespace QingChen\Admin\Layout;

use Closure;
use think\facade\View;
use think\Exception;
use QingChen\Admin\Support\Interfaces\Renderable;

class Content implements Renderable
{
    /**
     * Content title.
     *
     * @var string
     */
    protected $title = ' ';

    /**
     * Admin name.
     *
     * @var string
     */
    protected $adminName = '';

    /**
     * Content description.
     *
     * @var string
     */
    protected $description = ' ';

    /**
     * @var Row[]
     */
    protected $rows = [];

    /**
     * view object
     *
     * @var object
     */
    protected $view;

    /**
     * view object
     *
     * @var object
     */
    protected $content;

    /**
     * page type
     *
     * @var string
     */
    protected $pageType;

    /**
     * view path.
     *
     * @var string
     */
    protected $viewPath;

    /**
     * view path.
     *
     * @var string
     */
    protected $consolePath;

    /**
     * Content constructor.
     */
    public function __construct()
    {
        // 加载配置文件
        // Config::load('./../extend/encore/config/admin.php','','admin');
        // 读取配置项信息
        // $viewPath       = Config::get('view_path','admin');

        $viewPath          = config('admin.view_path').'/views';
        $adminName         = config('admin.admin_name');
        $this->viewPath    = $viewPath;
        $this->consolePath = $viewPath.'/console.html';
        $this->adminName   = $adminName;
        //初始化视图类
        $this->view        = new View();
    }

    /**
     * Alias of method `title`.
     *
     * @param string $pageType
     *
     * @return $this
     */
    public function page($pageType = null){
        $this->pageType = $pageType;

        return $this;
    }

    /**
     * Alias of method `title`.
     *
     * @param string $header
     *
     * @return $this
     */
    public function header($header = '')
    {
        return $this->title($header);
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set description of content.
     *
     * @param string $description
     *
     * @return $this
     */
    public function description($description = '')
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Alias of method row.
     *
     * @param mixed $content
     *
     * @return $this
     */
    public function body($content)
    {
        $this->content = $content;
        return $this->row($content);
    }

    /**
     * Add one row for content body.
     *
     * @param $content
     *
     * @return $this
     */
    public function row($content)
    {
        if ($content instanceof Closure) {
            $row = new Row();
            call_user_func($content, $row);
            $this->addRow($row);
        } else {
            $this->addRow(new Row($content));
        }

        return $this;
    }

    /**
     * Add Row.
     *
     * @param Row $row
     */
    protected function addRow(Row $row)
    {
        $this->rows[] = $row;
    }

    /**
     * Build html of content.
     *
     * @return string
     */
    public function build()
    {
        ob_start();

        foreach ($this->rows as $row) {
            $row->build();
        }

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }

    /**
     * 渲染骨架内容.
     *
     * @return string
     * @throws \Exception
     */
    public function render()
    {
        $items = [
            'header'      => $this->title,
            'description' => $this->description,
            'adminName'   => $this->adminName,
            '_content_'   => $this->build(),
        ];

        return ($this->view)::fetch($this->consolePath,$items);
    }

    /**
     * 渲染Grid内容.
     *
     * @return string
     * @throws \Exception
     */
    public function renderGrid()
    {
        $content = $this->content->getColumn();

        $column = <<<COLUMN
             $content
COLUMN;

        // dump($this->content->getImageColumn());
        // exit;
        $items = [
            'header'      => $this->title,
            'description' => $this->description,
            'column'      => $column,
            'imageColumn' => $this->content->getImageColumn()
        ];

        return View::fetch($this->viewPath.'/grid/grid.html',$items);
    }
}
