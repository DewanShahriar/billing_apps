<html>
<head>
    <base href='' />
    <meta charset="utf-8">
    <title>Client list</title>
    <style type="text/css" media="all">

        body {
            font-family: 'bangla', sans-serif !important;
            font-size: 14px;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact;
            }
        }

        @page {
            header: page-header;
            footer: page-footer;
            margin: 20px 0px;
            padding: 0px;

        }
        
        
        
        @media print {
            body {
                font-size: 14px !important;
                font-family: 'bangla', sans-serif !important;
            }

        }

    </style>

</head>

<body>
    
        <table border="0px" width="98%" align="center" style="border-collapse:collapse; margin:2px auto; padding-top: 50px">
            <tr>
                <td style="width:1.5in; text-align:center;"></td>

                <td style="text-align:center;">
                    <font style="font-size:25px; font-weight:bold; color:blue;"></font>

                    

                    <font style="font-size:16px; font-weight:bold;">
                      
        				<br>
                        </font>

                </td>

                <td style="width:1.2in; text-align:left;">
                  
                </td>

            </tr>
        </table> 
       

         

        <table style="width:95%; margin-left:48px;border-color:lightgray;border-collapse:collapse;margin-top:10px;" cellpadding="0" cellspacing="0">
            
            <tr>
                <td style="text-align:center;font-size:20px;font-weight:bold;padding-bottom: 10px">
                    <font style="">
                        <u>Client list</u>
                    </font>
                </td>
            </tr>

            <tr>
                <td style="text-align:center;font-size:18px;font-weight:bold;padding-bottom: 5px">
                   
                </td>
            </tr>

            
        </table>

        <table border="0" width="95%" align="center" style="border-collapse:collapse; margin:10px auto;">
            
  
            <tr>
              @foreach($district as $item)
                @if($item->en_name !='')
                <td style="padding-left: 50px;width: 150px;"><b>District </b></td>
                <td>
                    <font>&nbsp;:&nbsp;
                      {{$item->en_name}}
                    </font>
                </td>
                @endif
              @endforeach

               <!-- <td align='right'><b>Invoice no </b> </td>
                <td>
                    <font>&nbsp;:&nbsp;</font>
                </td> -->

            </tr>
            <tr>
              @foreach($upazilla as $item)
                @if($item->en_name !='')
                <td style="padding-left: 50px"><b>Upazilla</b> </td>
                <td>
                    <font>&nbsp;:&nbsp;
                      {{$item->en_name}}
                    </font>
                </td>
                @endif
              @endforeach
                <!-- <td align='right'><b>Generated date </b> </td>
                <td>
                    <font>&nbsp;:</font>
                </td> -->
            </tr>
            <tr>
                <!-- <td style="padding-left: 50px"><b>Mobile no</b></td>
                <td>
                    <font>&nbsp;:&nbsp;</font>
                </td> -->
                <!-- <td align='right'><b>Last payment date </b> </td>
                <td >
                    <font>&nbsp;:&nbsp;</font>
                </td> -->
            </tr>
        </table>

     
    <table class="jolchap" align="center" border="1"  width='95%' cellspacing="0" cellspacing='0' style="border-collapse:collapse;margin:0 auto;table-layout:fixed;">
      
      <tr>
          <td style="text-align: center;width: 4%;">SL</td>
          @if($district =='[]')
          <td style="text-align: center;width: 10%;">District</td>
          @endif
          @if($upazilla =='[]')
          <td style="text-align: center;width: 10%;">Upazilla</td>
          @endif
          <td style="text-align: center;width: 35%;">Name</td>
          <td style="text-align: center;width: 14%;">Mobile</td>
          <td style="text-align: center;width: 9%;">Unit fee</td>
          <td style="text-align: center;width: 9%;">Type</td>
          <td style="text-align: center;width: 9%;">Status</td>
      </tr>
      @php 
        $sl = 1;
      @endphp
      @foreach($data['data'] as $item)
      <tr>
          <td>{{$sl++}}</td>
          @if($district =='[]')
          <td>{{$item->district_name}}</td>
          @endif
          @if($upazilla =='[]')
          <td>{{$item->en_name}}</td>
          @endif
          <td>{{$item->name}}</td>
          <td>{{$item->mobile_no}}</td>
          <td align="right">{{$item->fee_amount}}&nbsp;</td>
          <td>@if($item->client_type == 1)
                Union
              @elseif($item->client_type == 2)
                Pourashava
              @elseif($item->client_type == 3)
                School
              @endif
          </td>
          <td>@if($item->status == 1)
                Active
              @elseif($item->status == 2)
                Inactive
              @endif
            </td>
      </tr>
      
      @endforeach
      
      <!-- <tr>
          <td colspan='6' style="text-align: right;">মোট &nbsp;</td>
          <td></td>
      </tr> -->
        

    </table>

               

</body>

</html>
