
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>ConvertX</title>
    <link rel="stylesheet" type="text/css" href="css/view.css" media="all">
    <script type="text/javascript" src="js/view.js"></script>

    <style>
        h1 {
            text-indent: 50px;
        }
        h1 a {
            display: none;
            height: 100%;
            min-height: 40px;
            overflow: hidden;
        }
    </style>

</head>
<body id="main_body" >

<img id="top" src="images/top.png" alt="">
<div id="form_container">

    <h1><a>ConvertX</a></h1>
    <form id="form_53469" class="appnitro" enctype="multipart/form-data" method="post" action="initiate.php">
        <div class="form_description">
            <h1>ConvertX</h1>
            <p>Simple tool to convert CSV files to multiple Formats</p>
            <?php
                /**
                 * Date: 11/10/2017
                 * Time: 17:24
                 */
                function reload ()
                {
                    echo '<meta http-equiv="refresh" content="5;url=index.php">';
                    header("Refresh:1, url=index.php",true,303); // start again
                }
                if(isset($_GET['action'])) {
                    if ("1" == $_GET["action"]) {
                        echo '<h1><span style="color:green">Success..!</span></h1>';
                        reload();

                    } else {
                        echo '<h1><span style="color:red">Failed.. Try again</span></h1>';
                        reload();
                    }
                    reload();
                }
            ?>

        </div>
        <ul >

            <li id="li_1" >
                <label class="description" for="upload">Upload a File </label>
                <div>
                    <input id="upload" name="upload" class="element file" type="file"/>
                </div> <p class="guidelines" id="guide_1"><small>please select a CSV file</small></p>
            </li>		<li id="li_2" >
            <label class="description" for="target">convert to? </label>
            <div>
                <select class="element select medium" id="target" name="target">
                    <option value="json" selected="selected">json</option>
                    <option value="xml">xml</option>
                    <option value="json_xml">both</option>

                </select>
            </div><p class="guidelines" id="guide_2"><small>select target format</small></p>
        </li>		<li id="li_3" >
            <label class="description" for="sort">sort ouput data </label>
            <div>
                <select class="element select medium" id="sort" name="sort">
                    <option value="" selected="selected"></option>
                    <option value="1, name" >name (A-Z)</option>
                    <option value="2, name" >name (Z-A)</option>
                    <option value="2, stars" >star (5-0)</option>
                    <option value="1, stars" >star (0-5)</option>

                </select>
            </div><p class="guidelines" id="guide_3"><small>select sorting options</small></p>
        </li>		<li id="li_4" >
            <label class="description" for="validation">need validation </label>
            <span>
			<input id="validation" name="validation" class="element checkbox" type="checkbox" checked="true" value="1" />
            <label class="choice" for="validation">Yes</label>

		</span><p class="guidelines" id="guide_4"><small>selecting no will result fast procressing</small></p>
        </li>

            <li class="buttons">
                <input type="hidden" name="form_id" value="53469" />

                <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
            </li>
        </ul>
    </form>
</div>
<img id="bottom" src="images/bottom.png" alt="">
</body>
</html>