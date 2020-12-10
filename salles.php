<?php
	header("Content-type: text/css; charset: UTF-8");
	$width=10;
?>

<style>

html{
	text-align:center;
}

body{
	padding:0;
	margin:0;
	display:flex;
	flex-direction: column;
	justify-content: center;
	font-family:Syne, Courier;
}

table{
	align-self:center;
	background: linear-gradient(0deg, rgba(214,231,241,0.6) 0%, rgba(120,171,222,1) 100%);
}

tr{
	display:flex;
	justify-content: space-around;
}

tbody{
}

td{
	width: <?php echo $width?>vw;
	height:80vh;
	display:flex;
	flex-direction:column;
	border:none;
	padding:0;
	border-right:3px solid grey;
}

td:last-child{
	border-right:none;
}

#tr_hr{
	margin-left:1%;
}

#col_time{
	display:flex;
	flex-direction:column;
	justify-content: space-between;
	align-items: center;
}

h1{
	margin:0;
}

h2{
	font-size:1vw;
	margin:0.5%;
}

h3{
	font-size:0.8vw;
	margin:0.5%;
}

h4{
	font-size:0.7em;
	margin:0.3%;
}

#block_right{
	display:flex;
	flex-direction: column-reverse;
}

#nav_buttons{
	display:flex;
	justify-content: center;
	align-items:center;
}

.btn-success{
	background:transparent;
	border:none;
	color:red;
}

.btn-success:hover{
	cursor:pointer;
	transform:scale(1.3);
	color:green;
	transition: all 0.7s ease-in;
}

#ch_left_1{
	animation:mv_left 2s 0s infinite linear;
}
#ch_left_2{
	animation:mv_left2 2s 0s infinite linear;
}
#ch_right_1{
	animation:mv_right2 2s 0s infinite linear;
}
#ch_right_2{
	animation:mv_right 2s 0s infinite linear;
}

@keyframes mv_left{
	0%{transform:translateX(100%);opacity:1;}
	100%{transform:translateX(-100%);opacity:0;}
}

@keyframes mv_left2{
	0%{transform:translateX(-100%);opacity:0.5;}
	50%{transform:translateX(-200%);opacity:0;}
	75%{transform:translateX(0%);opacity:0;}
	95%{opacity:0.5;}
	100%{transform:translateX(-100%);opacity:1;}
}

@keyframes mv_right{
	0%{transform:translateX(-100%);opacity:1;}
	100%{transform:translateX(100%);opacity:0;}
}

@keyframes mv_right2{
	0%{transform:translateX(100%);opacity:0.5;}
	50%{transform:translateX(200%);opacity:0;}
	75%{transform:translateX(0%);opacity:0;}
	95%{opacity:0.5;}
	100%{transform:translateX(100%);opacity:1;}
}

</style>