<?php
	header("Content-type: text/css; charset: UTF-8");
	$width=10;
?>

<style>

.bug_dodge{
}

html{
	height:100vh;
	width:100vw;
}

body{
	padding:0;
	margin:0;
	display:flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	font-family:Syne, Courier;
	position:relative;
	width:100%;
	height:100%;
	background:#6386AA;
}

#block_right{
	position:relative;
	display:flex;
	align-items:center;
	justify-content: center;
	flex-direction: column-reverse;
	width:100%;
}

table{
	position:relative;
	width:100%;
	height:100%;
	background: linear-gradient(0deg, rgba(214,231,241,0.6) 0%, rgba(120,171,222,1) 100%);
	align-self:center;
}

tr{
	display:flex;
	justify-content: space-around;
	width:100%;
	position:relative;
}

tbody{
	height:100%;
	position:relative;
}

#th_hr{
	margin-left:1%;
}

#tr_body{
	height:100%;
	position:relative;
}

#tr_body td{
	height:100%;
}

thead{
	display:flex;
	height:15%;
	align-items:center;
	justify-content: center;
}

td{
	width:100%;
	position:relative;
	display:flex;
	flex-direction:column;
	padding:0;
	border-right:3px solid;
	border-image:linear-gradient(0deg, rgba(205,205,205,0) 0%, #7a7a7a 30%, #7a7a7a 70%, rgba(229,140,250,0) 100%);
	border-image-slice:1;
}

td:last-child{
	border-right:none;
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

#form_slot{
	visibility: hidden;
	opacity:0;
	transition:all 0.8s ease-in;
}

#submit_slot{
	transition:all 0.5s ease-in;
	background:#8BBEF0;
	border:none;
	border-radius:10%;
	font-family:'Syne', Courier;
}

#submit_slot:hover{
	cursor:pointer;
	background:#A1E3A1;
	transition:all 0.3s ease-in;
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

footer{
	position:relative;
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