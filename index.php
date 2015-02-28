
$Key='SDL051648875';  //this is ISBN no for crawling book details from snapdeal
  $target_url="http://www.snapdeal.com/search?keyword=".$Key."&noOfResults=1";
  $xxx = getUrl($target_url); // the function from Step 3
  
  @$html->load($xxx); 
  foreach($html->find('a.hit-ss-logger') as $link) { 
                               $new_path=$link->href;
                                break;
                }
if($new_path!=""){
 
    $second1=getUrl($new_path); // the function from Step 3
    $second=str_replace("lazySrc","src",$second1);
    @$html->load($second);
      foreach($html->find('div.productTitle h1') as $e4)
        {   
          $title=($e4->plaintext);
        
        } 
        // below mysql code for insert data of book to database
        $insert=mysql_query("insert into shoose_snapdeal(supc_no,title_name,crawaled_date)
            values('".mysql_real_escape_string($Key)."','".mysql_real_escape_string($title)."',now())")or die(mysql_error()); 
         
        $last_id=mysql_insert_id();
       
        $k=1;
        foreach($html->find('img.jqzoom') as $link)
        {
         $image_path=$link->src;
         $headers=getHeaders($image_path); //this is for set header for dowload image
         $path='upload/'.$Key.'_'.$k.'.jpg';
         if ((($headers['http_code'] === 403)||($headers['http_code'] === 200)  )&& $headers['download_content_length'] < 1024*1024*1024) {
           if (download($image_path,$path)){  //download function for dowload image from remote server
               if($k==1){ // below mysql query for save image path/name in my database where it downloaded
                $SQL=mysql_query("Update shoose_snapdeal set  image_path1='".mysql_real_escape_string($path). "' Where supc_no ='".$Key."'");
               }elseif($k==2){
                $SQL=mysql_query("Update shoose_snapdeal set  image_path2='".mysql_real_escape_string($path). "' Where supc_no ='".$Key."'"); 
               }elseif($k==3){
                $SQL=mysql_query("Update shoose_snapdeal set  image_path3='".mysql_real_escape_string($path). "' Where supc_no ='".$Key."'"); 
               }elseif($k==4){
                $SQL=mysql_query("Update shoose_snapdeal set  image_path4='".mysql_real_escape_string($path). "' Where supc_no ='".$Key."'"); 
               }elseif($k==5){
                $SQL=mysql_query("Update shoose_snapdeal set  image_path5='".mysql_real_escape_string($path). "' Where supc_no ='".$Key."'"); 
               }                           }
                   }
        $k++;
        unset($image_path);unset($path);
        }
        
        
       
        foreach($html->find('span#original-price-id') as $e4)
        {
       
           $mrp_price=($e4->plaintext);
        }
           foreach($html->find('span#selling-price-id') as $e5)
        {
       
          $selling_price=($e5->plaintext);
        }
         $speci1='';
         $k=1;
           foreach($html->find('ul.key-features li') as $e8)
        {
               if($k<16)
               { if((preg_match('/SUPC:/',$e8->plaintext))||(preg_match('/Disclaimer:/',$e8->plaintext)))
               {}else{    
                   
                   $speci1.=$e8->plaintext;
                   $speci1.='~';}
               }
              
               
               
       if (preg_match('/Sleeves :/',$e8->plaintext))
                {
                  $data1=explode('Sleeves :',$e8->plaintext);
                  
                  $Sleeves_type=trim($data1[1]);
             
               }elseif (preg_match('/Wearability :/',$e8->plaintext))
                {
                  $data1=explode('Wearability :',$e8->plaintext);
                 
                 $Wearability_type=trim($data1[1]);
             
               }elseif (preg_match('/Colour :/',$e8->plaintext))
                {
                  $data1=explode('Colour :',$e8->plaintext);
                  
                  $color=trim($data1[1]);
             
               }elseif (preg_match('/Type : /',$e8->plaintext))
                {
                  $data1=explode('Type : ',$e8->plaintext);
                  
                  $shoose_type=trim($data1[1]);
             
               }elseif (preg_match('/Sole Material :/',$e8->plaintext))
                {
                  $data1=explode('Sole Material :',$e8->plaintext);
                  
                   $sole_material=trim($data1[1]);
             
               }
            $k++;   
           
        }
        
       $specification= rtrim($speci1, "~");
          foreach($html->find('div.details-content') as $g5)
        {
       
          $desc=($g5->plaintext);
        }
      
         $SQL=mysql_query("Update shoose_snapdeal set  sleeves_type='".mysql_real_escape_string($Sleeves_type). "',
             wearability_type='".mysql_real_escape_string($Wearability_type). "',
             colour='".mysql_real_escape_string($color). "',
             shoose_type='".mysql_real_escape_string($shoose_type). "',
             shoose_sole='".mysql_real_escape_string($sole_material). "',
             mrp='".mysql_real_escape_string($mrp_price). "',
             sellling_price='".mysql_real_escape_string($selling_price). "',
             description='".mysql_real_escape_string($desc). "',
             specification_bunch='".mysql_real_escape_string($specification). "',     

             type='".mysql_real_escape_string($type_product). "' Where supc_no ='".$Key."'"); 
      
       
                  
        
