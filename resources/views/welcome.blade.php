<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="description" content=""/>
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    
        
    <title>Начальная страница</title>

    <link href="styles2.css" rel="stylesheet" />
    <style>
   .topnav {
    background-color: #333;
    overflow: hidden;
  }
  
  /* Style the links inside the navigation bar */
  .topnav a {
    float: left;
    display: block;
    color: #f2f2f2;
    text-align: center;
    padding: 14px 26px;
    text-decoration: none;
    font-size: 17px;
  }
  
  /* Change the color of links on hover */
  .topnav a:hover {
    background-color: #ddd;
    color: black;
  }
  
  /* Add an active class to highlight the current page */
  .topnav a.active {
    background-color: #04AA6D;
    color: white;
  }
  
  /* Hide the link that should open and close the topnav on small screens */
  .topnav .icon {
    display: none;
  }
  
  /* When the screen is less than 600 pixels wide, hide all links, except for the first one ("Home"). Show the link that contains should open and close the topnav (.icon) */
  @media screen and (max-width: 600px) {
    .topnav a:not(:first-child) {display: none;}
    .topnav a.icon {
      float: right;
      display: block;
    }
  }
  
  /* The "responsive" class is added to the topnav with JavaScript when the user clicks on the icon. This class makes the topnav look good on small screens (display the links vertically instead of horizontally) */
  @media screen and (max-width: 600px) {
    .topnav.responsive {position: relative;}
    .topnav.responsive a.icon {
      position: absolute;
      right: 0;
      top: 0;
    }
    .topnav.responsive a {
      float: none;
      display: block;
      text-align: left;
    }
  }

  .tleft {
    text-align: left;
    float: left;
  }

  .tright {
    text-align: right;
    float: right;
  }

  .colortext {
    font-size: 28px;
    color: red; /* Красный цвет выделения */
}


  #layout_wrapper {
    margin: 0 auto;
    width: 900px;
  }
  
  section {
    background-color: pink;
    padding: 0 370px;
  }


  a {
    color: blue;
    text-decoration: underline;
  }
  
  a:hover {
     text-decoration: underline;
     opacity: 0.8;
     border: 15 px;
     color: blue;
  }
  
  a:active {
    color: blue;
  }
  
  p {
    text-align: right;
    float: right;
  } 
  .red {
    font-size: 18px;
    color: red;
  }
  .form-group label {
    display: block;
    font-size: 14px;
    color: #86898f;
  }
  
  .form-group input,
  .form-group textarea {
    width: 100%;
    box-sizing: border-box;
    border: none;
    background: transparent;
    border-bottom: 1px solid #ccc;
    color: #000;
    padding: 15px 0 10px;
    outline: none;
    font-family: inherit;
  }
  
  
  .row {
    display: flex;
    margin-bottom: 20px;
  }
  
  .row > .form-group {
    width: 20%;
    margin-right: 20px;
  }
  
  .row > .form-group:last-child {
    margin-right: 0;
  }

  .row2 {
    display: flex;
    font-size: 8px;
    margin-bottom: 20px;
  }
  
  .row2 > .form-group {
    width: 20%;
    font-size: 8px;
   
  }

  .row2 > .form-group input {
    font-size: 8px;
  }
  
  /* .row2 > .form-group:last-child {
    margin-right: 0;
  } */

  table, th, td {
    text-align: center; 
    vertical-align: middle;
    border: none;
    
    border-collapse: separate;
    font-size: 16px;
  }
  th, td {
    padding: 5px;
    border: none;
  }

  * { box-sizing: border-box; }
body {
  font: 16px Arial;
}
.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}
input[type=text] {
  background-color: #f1f1f1;
  width: 100%;
}
input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9;
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important;
  color: #ffffff;
}
  </style>
    </head>


<body>

<div class="topnav" id="myTopnav">
        <a href="{{route('start.index')}}" class = "active">Найти новую конференцию</a>
        <a href="#about" @yield('class2') >Вторая страница</a>
        <a href="#about" @yield('class3') >Какие-нибудь картинки</a>
        <a href="#about" @yield('class5') >Поиск</a>
        <a href="#contact" @yield('class4') >Контакты</a>
        <a href="#about">About</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
          
        </a>
      </div>
</br></br></br>
<div id="layout_wrapper">

Создание краткого содержания новых видеоконференций от генерального разнорабочего<br><br>
<a href="{{ route('start.index') }}">Вперед</a><br><br>



</div>


<script type="text/javascript">
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }

</script>


</body>
</html>