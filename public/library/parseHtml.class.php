<?php
/*
array('html'=>array(
        'tagName'=>'div',
        'attributes'=>array(
                0=>array('id'=>1),
            ),
        'Children'=>array(
                0=>array('nodeVal'=>''),
                1=>array(
                    'tagName'=>'p',
                    'attr'=>array(),
                    'children'=>array(
                        array('nodeVal'=>'')))
            )
        ));
*/
class parseHtml 
{
    public function __construct($html)
    {
        //$this->dom = $this->parseHtml($html);
    }


    public function parseHtml($html)
    {
        //$html = $this->clearBoth($html);
        $dom  = $this->explainHtml($html);
        return $dom;
    }

    public function explainHtml(&$html)
    {
        //txt<p>txt</p>txt<br>txt
        //<txt></txt><p></p><txt></txt><br><txt></txt
        $result = array();
        $i = 0;
        while(1)
        {
            //标签内-1.文本；2.标签
            $txt_cont = $this->leftTxt($html);
            if(trim($txt_cont))
            {
                $result[$i] = array('nodeValue'=>$txt_cont);
            }
            else if($this->hasTagHead($html))
            {
                //标签开始,获得标签头信息
                $tag_head  = $this->getTagHead($html);
                //标签内容
                $tag_body  = $this->explainHtml($html);
                //标签结束,回收闭合标签
                $tag_foot  = $this->getTagFoot($html, $tag_head['tagName']);

                $result[$i] = $tag_head;
                if(is_array($tag_body))
                {
                    $result[$i]['childNodes'] = $tag_body[0];
                }
            }
            else
            {
                return $result;
            }
            $i++;
            
        }
    }
    //获取当前html中左侧文本
    public function leftTxt(&$html)
    {
        preg_match('/([\s\S]*?)(<\s*([a-zA-Z]+)(\s?[^>]*)?>[\s\S]*)/', $html, $matches);
        var_export($matches);exit;
        $html   = $matches[2];
        $result = $matches[1];
        return $result;
    }
    public function clearBoth(&$html)
    {
        $html = substr($html, strpos($html, '<'));
        $html = substr($html, 0, strrpos($html, '>')+1);
        return $html;
    }
    //当前标签左侧是否存在标签头
    public function hasTagHead($html)
    {
        preg_match('/^<\s*([a-zA-Z]+)\s?([^>]*?)>/', $html, $matches);
        if(empty($matches))
        {
            return false;
        }
        return true;
    }
    //获得当前html左侧标签头信息
    public function getTagHead(&$html)
    {
        preg_match('/^<\s*([a-zA-Z]+)\s?([^>]*?)>([\s\S]*)/', $html, $matches);
        $attributes = $this->parseAttributes($matches[2]);
        $html = $matches[3];
        $result = array('tagName'=>$matches[1], 'attributes'=>$attributes);
        return $result;
    }
    public function getTagFoot(&$html, $tag_name)
    {
        preg_match('/^<\s*\/\s*([a-zA-Z]+)\s*>([\s\S]*)/', $html, $matches);
        if($tag_name==$matches[1])
        {
            $html = $matches[2];
        }
    }
    private function parseAttributes($attributes)
    {
        $result = array();
        $attributes = preg_replace('/\s*=\s*/', '=', $attributes);
        $attributes = preg_split('/\s+/', $attributes);
        foreach($attributes as $attribute)
        {
            if($attribute=='')
            {
                continue;
            }
            $attr_data = explode('=', $attribute);

            $result[$attr_data[0]] = isset($attr_data[1])?$attr_data[1]:true;
            
        }
        return $result;
    }

    public function getDom()
    {
        return $this->dom;
    }
}

//<div id="ccc" class    = "ccc" disabled   >
/*
$html =<<<EOF
</div>
<div id="ccc" class    = "ccc" disabled   >
    <span id="ccc" class="ccc">bbbb</span>
    <span id="ddd">cccccc</span>
</div>
<p>
    <span id="bbb" class="ccc">bbbbb</span>
    <span id="aaa">zzzzz</span>
</p>
<t></t>
EOF;

$hander = new parseHtml($html);
$t = $hander->explainHtml($html);
var_dump($t);
*/
