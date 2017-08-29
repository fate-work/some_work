<?php
function showForm($errors=''){
    //Если переданны ошибки вывести
    if($errors){
        echo "Please correct these errors: <ul><li>";
        echo implode("</li><li>",$errors);
        echo "</li></ul>";
    }
    echo <<<PHP
<form method="POST" action="index.php">
    <input type="text" name="name">

    <br>
    <input type="submit">
</form>
PHP;

}
function processForm(array $array){
    echo "<details>";
    echo "<summary>Спойлер</summary>";
    echo "<p>Hello, " . $array['name'] . "</p>";
    echo "</details>";
}
function validateForm(){
    $errors=[];
    $input=[];
    $input['name']=filter_input(INPUT_POST,'name',FILTER_VALIDATE_INT,['options'=>['min_range'=>18,'max_range'=>90]]);
    if(is_null($input['name']) || ($input['name'] === false)){
        $errors[]='Please enter valid age beetwen 18 and 90';
    }
   /* if(strlen($input['name']) <2){
         $errors[]="Your data must be at leat 3 letters long";
    }*/
    if(strlen(trim(strip_tags($input['name']))) == 0){
        $errors[]='Your name is required';
    }
    return [$errors,$input];
}
if("POST" ==$_SERVER["REQUEST_METHOD"]){
    //Если функция validate_form() возвращает ошибки,
    //передать их функции show_form()
    list($form_errors,$input)=validateForm();
    if($form_errors){
        showForm($form_errors);
    }else{
         processForm($input);
    }
}else{
    showForm();
}