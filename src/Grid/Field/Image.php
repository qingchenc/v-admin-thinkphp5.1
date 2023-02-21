<?php

namespace QingChen\Admin\Grid\Field;

class Image
{
    /**
     * 图片名称(image)
     *
     * @var mixed|string
     */
    protected $imageName;

    /**
     * 图片对应的字段名称(path)
     *
     * @var mixed|string
     */
    protected $imageFieldName;

    /**
     * 图片对应的模板名称(#imagePath)
     *
     * @var mixed|string
     */
    protected $imageTemplateName;

    /**
     * @param $image
     * @param $imageName
     * @param $imageTemplate
     */
    public function __construct($image = '',$imageName = '',$imageTemplate = '')
    {
        $this->imageName         = $image;

        $this->imageFieldName    = $imageName;

        $this->imageTemplateName = $imageTemplate;
    }

    /**
     * 输出图片的模板视图
     *
     * @return string
     */
    public function preview()
    {
        $dir = DIRECTORY_SEPARATOR;

        return <<<IMAGE
            <script type="text/html" id="$this->imageTemplateName">
                <img style="display: inline-block;height: 100%;" src="$dir{{d.$this->imageName.$this->imageFieldName}}">
            </script>
IMAGE;
    }
}