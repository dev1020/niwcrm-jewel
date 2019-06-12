<!DOCTYPE html>
<html>
<head>
    <title>Print Invoice</title>
    <style>
        *
        {
            margin:0;
            padding:0;
            font-family:Arial;
            font-size:10pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            font-size:10pt;
            margin:0;
            padding:0;
        }
         
        p
        {
            margin:0;
            padding:0;
        }
         
        #wrapper
        {
            width:180mm;
            margin:0 0mm;
        }
         
        .page
        {
            height:297mm;
            width:210mm;
            page-break-after:auto;
        }
 
        table
        {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            border-spacing:0;
            border-collapse: collapse; 
             
        }
         
        table td 
        {
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 2mm;
        }
         
        table.heading
        {
            height:50mm;
        }
         
        h1.heading
        {
            font-size:14pt;
            color:#000;
            font-weight:normal;
        }
         
        h2.heading
        {
            font-size:9pt;
            color:#000;
            font-weight:normal;
        }
         
        hr
        {
            color:#ccc;
            background:#ccc;
        }
        #head-top,#head-top td 
        {
            border-right: 0px solid #ccc;
            border-bottom: 0px solid #ccc;
            border-left: 0px solid #ccc;
            border-top: 0px solid #ccc;
        }
        #invoice_body
        {
            height: 149mm;
        }
         
        #invoice_body , #invoice_total
        {   
            width:100%;
        }
        #invoice_body table , #invoice_total table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
     
            border-spacing:0;
            border-collapse: collapse; 
             
            margin-top:5mm;
        }
         
        #invoice_body table td , #invoice_total table td
        {
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding:2mm 0;
        }
        
         
        #invoice_body table td.mono  , #invoice_total table td.mono
        {
            font-family:monospace;
            text-align:right;
            padding-right:3mm;
            font-size:10pt;
        }
         
        #footer
        {   
            width:180mm;
            margin:0 0mm;
            padding-bottom:3mm;
        }
        #footer table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            background:#eee;
             
            border-spacing:0;
            border-collapse: collapse; 
        }
        #footer table td
        {
            width:25%;
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>


<div id="wrapper">
    <table id="head-top" style="width:100%;">
        <tr>
            <td style="width:30%;"><img style="width:60mm" src="http://saltlake.in/images/logo.png" alt="" /></td>
            <td style="width:70%; padding-left:80px;padding-top: 25px"><h1 style="font-size:20pt;color:#000;font-weight:bold;">ORDER INVOICE</h1></td>
        </tr>
    </table>
    
    <table class="heading" style="width:100%;">
        <tr>
            <td style="width:80mm;">
                <h1 class="heading">Saltlake.In Web Services LLP</h1>
                <h2 class="heading">
                    AL-255<br />
                    Salt Lake - 700091<br />
                    sector-2 , West Bengal<br />
                     
                    Website : www.saltlake.in<br />
                    E-mail : support@saltlake.in<br />
                    Phone : 033-4072-6600
                </h2>
            </td>
            <td  valign="top" align="right" style="padding:3mm;">
                <table>
                    <tr><td>Invoice No : </td><td><?= str_pad($order->id, 10, '0', STR_PAD_LEFT) ?></td></tr>
                    <tr><td>Dated : </td><td><?= date("d-m-Y", strtotime($order->order_date))?></td></tr>
                    <tr><td>Currency : </td><td>INR</td></tr>
                    
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>Billing</b> :<br />
                <?= ucwords(strtolower($order->cust->name)) ?><br />
            Client Address
                <br />
                <?php if(isset($order->cust->address)){ 
                        echo $order->orderBpostdetail->bpostCreatedBy->address;                                
                      }
                      
                ?><br />
            </td>
            
        </tr>
    </table>
         
         
    <div id="content">
         
        <div id="invoice_body">
            <table>
            <tr style="background:#eee;">
                <td style="width:8%;"><b>Sl. No.</b></td>
				<td style="width:25%;"><b>Session</b></td>
                <td><b>Services.</b></td>
                
                
                <td style="width:15%;"><b>Price</b></td>
            </tr>
            </table>
             
            <table>
            <?php 
                $count = 1;
				
                foreach($order->ordersDetails as $details){?>
                <tr>
                    <td style="width:8%;"><?= $count ?></td>
					<td class="mono" style="width:25%;"><?= $details->session_no ?></td>
                    <td style="text-align:left; padding-left:10px;"><?= $details->services->name ?></td>
                    <td style="width:15%;" class="mono"><?= $details->services_price ?></td>
                </tr>
            <?php  $count++; } ?>
                    
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td></td>
            </tr>
             
            <tr>
                <td colspan="2"></td>
                <td>Total :</td>
                <td class="mono">&#8377; <?= $order->total_amount ?></td>
            </tr>
        </table>
        </div>
        <div id="invoice_total">
            Total Amount :
            <table>
                <tr>
                    <td style="text-align:left; padding-left:10px;"><?= Yii::$app->formatter->asSpellout($order->total_amount) ?></td>
                    <td style="width:15%;">INR</td>
                    <td style="width:15%;" class="mono">&#8377; <?= $order->total_amount ?></td>
                </tr>
            </table>
        </div>
        
         
        
    </div>
     
    
     
    </div>
     
    <htmlpagefooter name="footer">
        
        <div id="footer"> 
            <table>
                <tr><td>Software Solutions</td><td>Mobile Solutions</td><td>Web Solutions</td></tr>
            </table>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="footer" value="on" />
     
</body>
</html>