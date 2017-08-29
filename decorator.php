<?php

interface Shape
{
    public function draw();
}

class Circle implements Shape
{
    public function draw()
    {
        echo __CLASS__;
    }
}

class Rectangle implements Shape
{
    public function draw()
    {
        echo __CLASS__;
    }
}

class Square implements Shape
{
    public function draw()
    {
        echo __CLASS__ . "<br>";
    }
}
abstract class ShapeDecorator implements Shape
{
    protected $decorate;
    public function __construct(Shape $decorator)
    {
        $this->decorate=$decorator;
    }
    public function draw()
    {
        $this->decorate->draw();
    }
}
class Decorator extends ShapeDecorator
{
    public function __construct(Shape $decorator)
    {
        parent::__construct($decorator);
    }
    public function draw()
    {
        $this->redBorder();
        $this->decorate->draw();
    }
    public function redBorder()
    {
        echo "Red Border <br>";
    }
}
$s= new Square();
$s->draw();
$d= new Decorator($s);
$d->draw();