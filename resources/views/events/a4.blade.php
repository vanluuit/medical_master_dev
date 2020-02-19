
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>A4</title>
    <link rel="stylesheet" href="{{ URL::asset('/A4_files/normalize.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/A4_files/paper.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/A4_files/style.css')}}">
    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
      @page {
      size: A4
      }
      @page {
            padding: 0px 0px;
            margin: 0px;
        }
      @font-face {
        font-family: Sun-ExtA;
        src: url({{public_path('fonts/Sun-ExtA.ttf')}});
      }
      * {
        font-family: Sun-ExtA !important;
        font-weight: 400!important;
      }
      
    </style>
  </head>
  <!-- Set "A5", "A4" or "A3" for class name -->
  <!-- Set also "landscape" if you need -->
  <body class="A4">
    <div id="HTMLtoPDF">
      <!-- Each sheet element should have the class "sheet" -->
      <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
      <section class="sheet padding-10mm" data-print="section">
        <!-- Write HTML just like a web page -->
        <p class="text-bold mt0">別記様式第１</p>
        <div class="main border-frame">
          <div class="page">
            <h1 class="text-center mt5">消防用設備等（特殊消防用設備等）点検結果報告書</h1>
            <section class="top-content">
              <p class="text-right">
                <span class="text text-regular">2019</span>年<span class="text text-regular">07</span>月<span class="text text-regular">18</span>日
              </p>
              <p class="mt0">
                <span class="text text-regular">名古屋市熱田区 消防署長</span>
                <span class="text text-bold ml30">殿</span>
              </p>
              <div class="store-information">
                <h3 class="text text-bold letter-spacing">届出者</h3>
                <ul class="list-info">
                  <li class="item">
                    <span class="text-bold text">住&nbsp;&nbsp;&nbsp;&nbsp;所</span>
                    <span class="text text-regular ml15">東京都港区台場2-3-1 トレードピアお台場 17階</span>
                  </li>
                  <li class="item">
                    <span class="text-bold text">氏&nbsp;&nbsp;&nbsp;&nbsp;名</span>
                    <span class="text text-regular ml15">東海 太郎</span>
                    <span class="text-bold text ml-auto circle">印</span>
                  </li>
                  <li class="item">
                    <span class="text-bold text">電話番号</span>
                    <span class="text text-regular ml15">03-3599-9500</span>
                  </li>
                </ul>
              </div>
              <!-- /store-information -->
              <p>下記のとおり消防用設備等（特殊消防用設備等）の点検を実施したので、消防法第17条の３の３の規定に基づき報告します。</p>
            </section>
            <!-- /top -->
            <p class="text-center mt0">記</p>
            <div class="block-table">
              <table>
                <tbody>
                  <tr>
                    <td align="center" class="w30" rowspan="4">
                      <span class="text text-bold">防<br/>火<br/>対<br/>象<br/>物</span>
                    </td>
                    <td align="center" class="w140">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">所</span>
                        <span class="text text-bold">在</span>
                        <span class="text text-bold ml-auto">地</span>
                      </div>
                    </td>
                    <td><span class="text text-regular">愛知県名古屋市熱田区○○町1-1○○ビル 2F</span></td>
                  </tr>
                  <tr>
                    <td align="center">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">名</span>
                        <span class="text text-bold ml-auto">称</span>
                      </div>
                    </td>
                    <td><span class="text text-regular">○○飯店 1号店</span></td>
                  </tr>
                  <tr>
                    <td align="center">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">用</span>
                        <span class="text text-bold ml-auto">途</span>
                      </div>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td align="center"><span class="text text-bold letter-spacing">構造・規模</span></td>
                    <td>
                      <div class="row row-box-col">
                        <div class="col-auto d-flex">
                          <span class="text text-bold mr-auto">地上</span>
                          <span class="text text-regular ml-auto">4 <span class="text text-bold ml5">階</span></span>
                        </div>
                        <div class="col-auto d-flex ml30">
                          <span class="text text-bold mr-auto">地下</span>
                          <span class="text text-regular ml-auto">0<span class="text text-bold ml5">階</span></span>
                        </div>
                        <div class="col-auto d-flex ml30">
                          <span class="text text-bold mr-auto">延べ面積</span>
                          <span class="text text-regular ml-auto">65<span class="text text-bold ml5">m<sup>2</sup></span></span>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="h200" colspan="2">
                      <span class="text text-bold">消防用設備等（特殊消防用設備等）の種類等</span>
                    </td>
                    <td>
                      <span class="text"></span>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- /table -->
            </div>
            <!-- /block -->
            <div class="block-table">
              <table>
                <thead>
                  <tr>
                    <th class="text text-bold letter-spacing">※受付欄</th>
                    <th class="text text-bold letter-spacing">※経過欄</th>
                    <th class="text text-bold letter-spacing">※備考</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="h150"></td>
                    <td class="h150"></td>
                    <td class="h150"></td>
                  </tr>
                </tbody>
              </table>
              <!-- /table -->
            </div>
            <!-- /block -->
          </div>
        </div>
        <!-- /main -->
        <div class="note-container">
          <div class="d-flex align-item-baseline">
            <span class="text-bold text text-nowwrap">備考</span>
            <ul class="note-list">
              <li class="item">
                <span class="text-bold text">1&nbsp;この用紙の大きさは、日本産業規格Ａ４とすること。</span>
              </li>
              <li class="item">
                <span class="text-bold text">2&nbsp;点検者が複数の場合は、別紙に記入し、添付すること。</span>
              </li>
              <li class="item">
                <span class="text-bold text">3&nbsp;消防用設備等又は特殊消防用設備等ごとの点検票を添付すること。</span>
              </li>
              <li class="item">
                <span class="text-bold text">4&nbsp;※印欄は、記入しないこと。</span>
              </li>
            </ul>
          </div>
        </div>
      </section>
      <!-- /section -->
      <section class="sheet padding-10mm" data-print="section">
        <!-- Write HTML just like a web page -->
        <p class="text-bold mt0 d-flex">別記様式第１ <span class="text text-bold ml-auto">（その１）</span></p>
        <div class="main">
          <div class="page">
            <div class="block-table">
              <table>
                <tbody>
                  <tr>
                    <td align="center" colspan="6">
                      <span class="text text-bold letter-spacing">消火器具点検票</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="w65">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">名</span>
                        <span class="text text-bold ml-auto">称</span>
                      </div>
                    </td>
                    <td colspan="2">
                      <span class="text text-regular">○○飯店 1号店</span>
                    </td>
                    <td align="center" class="w65">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">防</span>
                        <span class="text text-bold ml-auto">火</span>
                      </div>
                      <span class="text text-bold">管理者</span>
                    </td>
                    <td colspan="2">
                      <span class="text text-regular">○○飯店 1号店</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="w65">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">所</span>
                        <span class="text text-bold ml-auto">在</span>
                      </div>
                    </td>
                    <td colspan="2">
                      <span class="text text-regular">愛知県名古屋市熱田区○○町1-1 ○○ビル 2F</span>
                    </td>
                    <td align="center" class="w65">
                      <span class="text text-bold">立会者</span>
                    </td>
                    <td colspan="2">
                      <span class="text text-regular">点検 三郎</span>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" class="w65">
                      <span class="text text-bold">点検種別</span>
                    </td>
                    <td class="w100" align="center"><span class="text text-bold letter-spacing">機器点検</span></td>
                    <td class="w90" align="center"><span class="text text-bold">点検年月日</span></td>
                    <td colspan="3">
                      <span class="text text-regular">2018</span>
                      <span class="text text-bold">年</span>
                      <span class="text text-regular">07</span>
                      <span class="text text-bold">月</span>
                      <span class="text text-regular">15</span>
                      <spa class="text text-bold">日</spa>
                      <!-- top -->
                      <span class="text text-bold">～</span>
                      <!-- bottom -->
                      <span class="text text-regular">2019</span>
                      <span class="text text-bold">年</span>
                      <span class="text text-regular">07</span>
                      <span class="text text-bold">月</span>
                      <span class="text text-regular">18</span>
                      <spa class="text text-bold">日</spa>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" class="w65" rowspan="2">
                      <span class="text text-bold">点検者</span>
                    </td>
                    <td align="left" rowspan="2">
                      <span class="text text-bold d-block">氏名</span>
                      <span class="text text-regular">東海次郎</span>
                    </td>
                    <td align="center" rowspan="2">
                      <span class="text text-bold d-block">点検者</span>
                      <span class="text text-bold d-block">所属会社</span>
                    </td>
                    <td colspan="3">
                      <div class="row row-box-col">
                        <div class="col-auto d-flex">
                          <span class="text text-bold">社名</span>
                          <span class="text text-regular ml10">○○○株式会社</span>
                        </div>
                        <div class="col-auto d-flex ml30">
                          <span class="text text-bold">TEL</span>
                          <span class="text text-regular ml10">03-3599-9500</span>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6">
                      <span class="text text-bold d-block">住所</span>
                      <span class="text text-regular d-block">東京都港区台場2-3-1トレードワーク 25階</span>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- /table -->
              <table>
                <tbody>
                  <tr>
                    <td align="center" rowspan="3" colspan="3">
                      <span class="text text-bold letter-spacing">点検項目</span>
                    </td>
                    <td align="center" colspan="8">
                      <span class="text text-bold letter-spacing">点検検検結検果</span>
                    </td>
                    <td align="center" rowspan="3">
                      <span class="text text-bold letter-spacing">措置内容</span>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" colspan="6">
                      <span class="text text-bold">消火器の種別</span>
                    </td>
                    <td class="w28" align="center" rowspan="2">
                      <span class="text text-bold">判定</span>
                    </td>
                    <td align="center" rowspan="2">
                      <span class="text text-bold letter-spacing">不良内容</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="w22" align="center">
                      <span class="text text-bold">A</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text text-bold">B</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text text-bold">C</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text text-bold">D</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text text-bold">E</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text text-bold">F</span>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" colspan="14">
                      <span class="text text-bold letter-spacing">機器点検</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="w10" align="center" rowspan="4">
                      <span class="text text-bold">設<br/>置<br/>状<br/>況<br/></span>
                    </td>
                    <td class="w279" align="center" colspan="2">
                      <span class="text text-bold letter-spacing">設置場所</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11">2</span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w100" align="center">
                      <span class="text text-b"></span>
                    </td>
                    <td class="w100" align="center">
                      <span class="text text-b"></span>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">設置間隔</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">適</span>
                        <span class="text text-bold">応</span>
                        <span class="text text-bold ml-auto">性</span>
                      </div>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">耐震措置</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="3">
                      <span class="text text-bold letter-spacing">表示・標識</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td class="w10" align="center" rowspan="13">
                      <span class="text text-bold">消<br/>火<br/>器<br/>の<br/>外<br/>形</span>
                    </td>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">本体容器</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">安全栓の封</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">安</span>
                        <span class="text text-bold">全</span>
                        <span class="text text-bold ml-auto">栓</span>
                      </div>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11">1</span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text text-b"></span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text text-b"></span>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">使用済みの表示装置</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">押し金具・レバー等</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">キャップ</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <div class="item-float-lr">
                        <span class="text text-bold mr-auto">ホ</span>
                        <span class="text text-bold">ー</span>
                        <span class="text text-bold ml-auto">ス</span>
                      </div>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11">1</span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text text-b"></span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text text-b"></span>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">ノズル・ホーン・ノズル栓</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">指示圧力計</span>
                    </td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center">
                      <span class="text-bold text circle font-11"></span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">圧力調整器</span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">安全弁</span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">保持装置</span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2">
                      <span class="text text-bold letter-spacing">車輪（車　載　式）</span>
                    </td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                    <td class="w22" align="center"></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /table -->
          </div>
        </div>
        <!-- /main -->
        <div class="note-container">
          <div class="d-flex align-item-baseline">
            <span class="text-bold text text-nowwrap">備考</span>
            <ul class="note-list">
              <li class="item">
                <span class="text-bold text">1&nbsp;この用紙の大きさは、日本産業規格Ａ４とすること。</span>
              </li>
              <li class="item">
                <span class="text-bold text">2&nbsp;消火器の種別欄は、該当するものについて記入すること。Ａは粉末消火器、Ｂは泡消火器、Ｃは強化液消火器、Ｄは二酸化炭素消火器、Ｅはハロゲン化物消火器、Ｆは水消火器をいう。</span>
              </li>
              <li class="item">
                <span class="text-bold text">3&nbsp;判定欄は、正常の場合は○印、不良の場合は不良個数を記入し、不良内容欄にその内容を記入すること。</span>
              </li>
              <li class="item">
                <span class="text-bold text">4&nbsp;選択肢のある欄は、該当事項に○印を付すこと。</span>
              </li>
              <li class="item">
                <span class="text-bold text">5&nbsp;措置内容欄には、点検の際措置した内容を記入すること。</span>
              </li>
            </ul>
          </div>
        </div>
      </section>
      <!-- /seciton -->
      <section class="sheet padding-10mm" data-print="section">
          <!-- Write HTML just like a web page -->
          <p class="text-bold mt0 d-flex">別記様式第１ <span class="text text-bold ml-auto">消火器具（その２）</span></p>
          <div class="main">
            <div class="page">
              <div class="block-table">
                <table>
                  <tbody>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">ガス導入管（車載式）</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td class="w65" align="center" rowspan="3" colspan="2">
                        <span class="text text-bold letter-spacing d-inline-block">本<br>体<br>容<br>器</span>
                        <span class="text text-bold letter-spacing d-inline-block">・<br>内<br>筒<br>等</span>
                      </td>
                      <td align="center">
                        <span class="text text-bold letter-spacing">本体容器</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"> </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w28" align="center"></td>
                      <td align="center"></td>
                      <td align="center"></td>
                    </tr>
                    <tr>
                      <td align="center">
                        <div class="item-float-lr">
                          <span class="text text-bold mr-auto">内</span>
                          <span class="text text-bold">筒</span>
                          <span class="text text-bold ml-auto">等</span>
                        </div>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center">
                        <span class="text text-bold">液面表示</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" rowspan="2" colspan="2">
                        <span class="text text-bold letter-spacing d-inline-block">薬<br>剤</span>
                        <span class="text text-bold letter-spacing d-inline-block">消<br>火</span>
                      </td>
                      <td align="center">
                        <div class="item-float-lr">
                          <span class="text text-bold mr-auto">性</span>
                          <span class="text text-bold ml-auto">状</span>
                        </div>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center">
                        <span class="text text-bold letter-spacing">消火薬剤量</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">加圧用ガス容器</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">カッター・押し金具</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <div class="item-float-lr">
                          <span class="text text-bold mr-auto">ホ</span>
                          <span class="text text-bold">ー</span>
                          <span class="text text-bold ml-auto">ス</span>
                        </div>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">開閉式ノズル・切替式ノズル</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">指示圧力計</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">使用済みの表示装置</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">圧力調整器</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">安全弁・減圧孔<br>（排圧栓を含む。）</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">粉上り防止用封板</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">パッキン</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text text-bold letter-spacing">サイホン管・ガス導入管</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <div class="item-float-lr">
                          <span class="text text-bold mr-auto">ろ</span>
                          <span class="text text-bold">過</span>
                          <span class="text text-bold ml-auto">網</span>
                        </div>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text-bold">放射能力</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w100" align="center">
                        <span class="text text-bold"></span>
                      </td>
                      <td class="w100" align="center">
                        <span class="text text-bold"></span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" colspan="3">
                        <span class="text-bold">消火器の耐圧性能</span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w28" align="center">
                        <span class="text text-bold"></span>
                      </td>
                      <td class="w100" align="center">
                        <span class="text text-bold"></span>
                      </td>
                      <td class="w100" align="center">
                        <span class="text text-bold"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="w35" align="center" rowspan="2">
                        <span class="text-bold d-inline-block">簡<br>易<br>消</span>
                        <span class="text-bold d-inline-block">火<br>用<br>具</span>
                      </td>
                      <td class="w200" align="center" colspan="2">
                        <div class="item-float-lr">
                          <span class="text text-bold mr-auto">外</span>
                          <span class="text text-bold ml-auto">形</span>
                        </div>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w28" align="center">
                        <span class="text text-bold"></span>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="2">
                        <div class="item-float-lr">
                          <span class="text text-bold mr-auto">水</span>
                          <span class="text text-bold">量</span>
                          <span class="text text-bold ml-auto">等</span>
                        </div>
                      </td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                      <td class="w22" align="center"></td>
                    </tr>
                  </tbody>
                </table>
                <!-- /table -->
                <table class="mt20">
                  <thead>
                    <tr>
                      <td class="w22" align="center">
                        <span class="text text-bold">備<br><br>考</span>
                      </td>
                      <td align="left" colspan="10">
                        <span class="text-regular text">点検報告書自動作成機能 おたすけ丸 により作成</span>
                      </td>
                    </tr>
                    <tr>
                      <th class="w39" align="center" rowspan="4">
                        <span class="text text-bold">測<br>定<br>機<br>器</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">機器名</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">型式</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">校正年月日</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">製造者名</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">機器名</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">型式</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">校正年月日</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">製造者名</span>
                      </th>
                    </tr>
                    <tr>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                    </tr>
                    <tr>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                    </tr>
                    <tr>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text"></span>
                      </th>
                      <th align="center">
                        <span class="text-regular text">東海 太郎</span>
                      </th>
                    </tr>
                  </thead>
                </table>
                <!-- /table -->
                <table class="mt10">
                  <thead>
                    <tr>
                      <th align="center">
                        <span class="text-bold text letter-spacing">器種名</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">設置数</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">点検数</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">合格数</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">要修理数</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text letter-spacing">廃棄数</span>
                      </th>
                    </tr>
                    <tr>
                      <th align="center">
                        <span class="text-regular text">粉末（蓄圧式）</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">3</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">3</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">2</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                    </tr>
                    <tr>
                      <th align="center">
                        <span class="text-bold text">強化液（加圧式）</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">0</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                    </tr>
                    <tr>
                      <th align="center">
                        <span class="text-bold text">粉末（加圧式）</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">0</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                    </tr>
                    <tr>
                      <th align="center">
                        <span class="text-bold text">強化液（蓄圧式）</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">0</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                      <th align="center">
                        <span class="text-bold text">1</span>
                      </th>
                    </tr>
                  </thead>
                </table>
                <!-- /table -->
              </div>
            </div>
          </div>
      </section>
      <!-- /seciton -->
      <section class="sheet padding-10mm" data-print="section">
        <div class="note-container">
          <div class="d-flex align-item-baseline">
            <span class="text-bold text text-nowwrap">備考</span>
            <ul class="note-list">
              <li class="item">
                <span class="text-bold text">1&nbsp;この用紙の大きさは、日本産業規格Ａ４とすること。</span>
              </li>
              <li class="item">
                <span class="text-bold text">2&nbsp;消火器の種別欄は、該当するものについて記入すること。Ａは粉末消火器、Ｂは泡消火器、Ｃは強化液消火器、Ｄは二酸化炭素消火器、Ｅはハロゲン化物消火器、Ｆは水消火器をいう。</span>
              </li>
              <li class="item">
                <span class="text-bold text">3&nbsp;判定欄は、正常の場合は○印、不良の場合は不良個数を記入し、不良内容欄にその内容を記入すること。</span>
              </li>
              <li class="item">
                <span class="text-bold text">4&nbsp;選択肢のある欄は、該当事項に○印を付すこと。</span>
              </li>
              <li class="item">
                <span class="text-bold text">5&nbsp;措置内容欄には、点検の際措置した内容を記入すること。</span>
              </li>
            </ul>
          </div>
        </div>
        <!-- /note -->
        <div class="panel-information">
          <div class="heading-block">
            <h3>別紙備考欄</h3>
          </div>
          <div class="content-info">
            <p class="text text-bold">［不良項目］：<span class="text text-regular">設置場所</span></p>
            <p class="text text-bold">［不良内容］：<span class="text text-regular">容易に持ち出せない状態であった。</span></p>
            <p class="text text-bold">［措置内容］：<span class="text text-regular">その他 新しい消火器に交換</span></p>
            <p class="text text-bold">［不良項目］：</p>
            <p class="text text-bold"> 商品名：<span class="text text-regular">PAN-10Z(IV)RF-RS</span></p>
            <p class="text text-bold"> 設置日：<span class="text text-regular">2016/05/09</span></p>
            <p class="text text-bold"> 廃棄日：<span class="text text-regular">2018/01/05</span></p>
            <p class="text text-bold"> 以下余白</p>
          </div>
        </div>
      </section>
      <!-- /seciton -->
    </div>
  </body>
</html>