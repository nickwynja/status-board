<!DOCTYPE html >
<html>
<head>
  <style>
    body {
      padding: 0; margin: 0;
    }

    #header {
      background-color: #222;
      position: fixed;
      width: 100%;
    }

    #container {
      padding-top: 50px;
    }

    h2 {
      border-bottom: 2px solid #111;
      color: #555;
      font-family: "Roadgeek 2005 Series C";
      font-weight: normal;
      font-size: 26px;
      padding: 13px 0 10px 0;
      margin: 0;
      text-align: center;
      text-transform: uppercase;
    }

    h3 {
      color: #808080;
      font-weight: normal;
      margin: 10px 0 10px 6px;
    }

    ul {
      font-size: 20px;
      list-style: none;
      padding-left: 20px;
      margin: 0;
    }

    li {
      list-style-type: none;
      padding: 0;
    }
  </style>
  <script type="text/javascript">
    function refresh() {
        var req = new XMLHttpRequest();

        req.onreadystatechange=function() {
          if (req.readyState == 4 && req.status == 200) {
          document.getElementById("container").innerHTML = req.responseText;
        }
      }
        req.open("GET", "update.php", true);
        req.send(null);
    }

    function init() {
      refresh();
      self.setInterval(function(){ refresh(); }, 10000);
    }
  </script>



</head>

<body onload="init()">
  <div id='header'>
    <h2>OmniFocus</h2>
  </div>
	    <div id="container">
	        Loading...
	    </div>
</body>
</html>