<html>
<head>
    <link rel="stylesheet" href="css/ui.css">
    <script src="jquery.js"></script>
    <script src="ui.js"></script>
    <script src="flot.js"></script>
    <script src="plugins.js"></script>
    <style>
        #graph {
            width:572px;
            height:800px;
        }
    </style>
</head>
<body>

<p id="after_inputs"><a id="add">Add some more</a></p>

<input type="radio" id="radio1" name="size" value="head" checked="checked">
<input type="radio" id="radio2" name="size" value="person">
<input type="radio" name="size" id="radio3" value="other">

<input id="img_width" type="text" value="5" style="width:40px;">

<p id="update-p"><a id="update"><b>Update!</b></a></p>
<div id="container"><div id="graph"></div>
    <p class="graph" id="yaxis">Theoretical blur disk diameter as percentage of image width [%]</p>
    <p class="graph" id="watermark">Generated by XXXX</p>
</div>

<div id="slider" style="font-size:0.8em;"></div>



<script src="main.js?<?=rand(1,9999)?>"></script>
</body>
</html>