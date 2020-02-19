<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Event Mypage</title>
	<style type="text/css">
		@font-face {
		  font-family: Sun-ExtA;
		  src: url({{public_path('fonts/Sun-ExtA.ttf')}});
		}
		@page {
            padding: 100px 0px;
            margin: 0px;
        }

        .header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 60px;
            border-bottom: 2px solid #ef7f19;
            padding-bottom: 20px;
        }

        .footer {
            position: fixed; 
            bottom: 0px; 
            left: 0px; 
            right: 0px;
            height: 60px;
            border-top: 2px solid #ef7f19;
            padding-top: 20px;

        }
        * {
        	font-family: Sun-ExtA !important;
        	font-weight: 400!important;
        }
        body {
        	font-family: Sun-ExtA !important;
        	font-weight: 400!important;
        	font-size: 14px;
        	width: 100%;
        	padding: 0px;
        	margin: 0px;
        }
        .main{
        	margin: 100px 0px;
        }
        #ct {
		  border-collapse: collapse;
		}
        #ct, #ct th, #ct td{
        	border: 2px solid #ef7f19;
        }
        #ct th, #ct td{
        	padding: 12px;
        }
        #ct th{
        	background: #ef7f19;
        	color: #fff;
        	font-size: 18px;
        }
        table {
        	width: 100%;
        }
        .cont{
        	padding: 0px 30px;
        }
	</style>
</head>
<body>
	<div class="header">
		<table>
			<tbody>
				<tr>
					<td>
						<div class="logo">
							<img src="{{route('login')}}/public/logo_pdf.png">
						</div>
					</td>
					<td>
						<p style="text-align: right; padding-right: 12px;">作成日：{{date('m月d日')}}</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="main">
		<div style="text-align: center; font-size: 30px; color: #ef7f19;">{{$association->category}}</div>
		<div class="cont">
			<table id="ct">
			<thead>
				<tr>
					<th>date</th>
					<th>content</th>
				</tr>
			</thead>
			<tbody>
				@if(count($events))
				@foreach($events as $k => $v)
				@foreach($v as $key => $value)
				<tr style="page-break-inside: avoid;">
					<td>{{ date('Y-m-d', strtotime($value->start_time)) }}</td>
					<td>
						<p style="font-size: 18px; color: #ef7f19">{{@$value->category->name}} {{@$value->theme->name}}</p>
						<p><span style="border: 1px solid red; color:red; padding: 4px">日程</span>{{date('H:i', strtotime($value->start_time))}} ~ {{date('H:i', strtotime($value->end_time))}}  </p>
						<p><span style="border: 1px solid red; color:red; padding: 4px">会場</span>{{$value->hall}}{{$value->floor}}{{$value->room}}</p>
						<p><span style="border: 1px solid red; color:red; padding: 4px">座長</span>{{$value->preside}}</p>
					</td>
				</tr>
				@endforeach
				@endforeach
				@endif
			</tbody>
		</table>
		</div>
		
	</div>
	<div class="footer">
		<table>
			<tbody>
				<tr>
					<td>
						<p style="font-size: 12px; padding-right: 12px;">Copyright © Medical Masters All Rights Reserved.</p>
					</td>
					<td style="text-align: right">
						<img src="{{route('login')}}/public/footer_pdf.png">
						株式会社メディカルマスターズ
					</td>
				</tr>
			</tbody>
		</table>
		
	</div>
</body>
</html>