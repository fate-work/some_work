<?php

class FormHelper
{
    protected $values;

    public function __construct(array $values = [])
    {
        if ("POST" == $_SERVER['REQUEST_METHOD']) {
            $this->values = $_POST;
        } else {
            $this->values = $values;
        }
    }

    public function input($type, $attributes = [], $isMultiple = false)
    {
        $attributes['type'] = $type;
        if (($type == 'radio') || ($type == 'checkbox')) {
            if ($type->isOptionSelected($attributes['name'] ?? null, $attributes['value'] ?? null)) {
                $attributes['checked'] = true;
            }
        }
        return $this->tag('input', $attributes, $isMultiple);
    }

    public function select($options, $attributes = [])
    {
        $multiple = $attributes['multiple'] ?? false;
        return $this->start('select', $attributes, $multiple) .
            $this->options($attributes['name'] ?? null, $options) .
            $this->end('select');
    }

    public function textarea($attributes = [])
    {
        $name = $attributes['name'] ?? null;
        $value = $this->values[$name] ?? '';
        return $this->start('textarea', $attributes) .
            htmlentities($value) . $this->end('textarea');
    }
    public function tag($tag,$attributes=[],$isMultiple=false)
    {
        return "<$tag {$this->attributes($attributes,$isMultiple)} />";
    }
    public function start($tag,$attributes=[],$isMultiple=false)
    {
        //Дескрипторы <select> i <textarea> не получают
        //атрибуты value
        $valueAttribute=(! (($tag == 'select') || ($tag == 'textarea')));
        $attrs=$this->attributes($attributes,$isMultiple,$valueAttribute);
        return "<$tag $attrs>";
    }
    public function end($tag)
    {
        return "</$tag>";
    }
    public function attributes($attributes,$isMultiplem,$valueAttribute=true)
    {
        $tmp=[];
        /*Если данный дескриптор может содержать атрибут value, а его имени  соответствует
        элемент в масиве значений, то установить этот атрибут*/
        if($valueAttribute && isset($attributes['name']) &&
                array_key_exists($attributes['name'],$this->values)){
            $attributes['value']=$this->values[$attributes['name']];
        }
        foreach ($attributes as $k=>$v){
            //Истинное логическое значение означает
            //логический атрибут
            if(is_bool($v)){
                if($v){$tmp[]=$this->encode($k);}
            }//иначе k=v
            else{
                $value=$this->encode($v);
                //Если это многозначный элемент, присоединить
                // квадратные скобки([]) к его имени
                if($isMultiplem && ($k == 'name')){
                    $value .="[]";
                }
                $tmp[]="$k=\"$value\"";
            }
        }
        return implode("",$tmp);
    }
    protected function isOptionSelected($name,$value)
    {
        //Если же для аргумента $name в массиве отсутствует элемент,
        //значит тот элемент нельзя выбрать
        if(!isset($this->values[$name])){
            return false;
        }
        //Если же для аргумента $name в массиве имеется элемент,
        //который сам является массивом,проверить находится ли значение аргумента
        //$value в массиве
        elseif (is_array($this->values[$name])){
            return in_array($value,$this->values[$name]);
        }
        //А иначе сравнить значение аргумента $value  с
        //элементом массива значений по аргументу аргумента $name
        else{
            return $value=$this->values[$name];
        }
    }
    public function encode($s)
    {
        return htmlentities($s);
    }
}