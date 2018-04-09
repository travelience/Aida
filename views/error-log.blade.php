<html>
  <head>
    <title>Whoops! Something broke! </title>
    <style>

      body
      {
        margin:0;
        padding:0;
        font-family:Arial;
      }

      h1
      {
         padding:40px;
         margin:0;
         background:#a21e0f;
         color:#fff;
         text-align:center;
         margin-bottom:40px;
      }

      .table
      {
        width: auto; 
        display: inline-block;
      }

      .row td,
      .row-2 td
      {
        padding:10px;
      }

      .row
      {
        background-color:rgb(230,230,230);
      }

      .row-2
      {
        background-color:rgb(240,240,240);
      }

      .container
      {
        text-align:center;
      }

      .field
      {
        width:130px;
        font-weight:bold;
      }

    </style>
  </head>
  <body>
  
    <h1> Whoops! </h1>
    
    <?php

      $data = [
        'Type' => get_class( $e ),
        'Message' => $e->getMessage(),
        'File' => $e->getFile(),
        'Line' => $e->getLine()
      ];

      $i=1;
    ?>

      <div class="container">

        <table class="table">
          
          <?php foreach($data as $key=>$val){ ?>
            
            <tr class="<?=$i%2==0 ? 'row-2' : 'row'?>" >
              <td class="field"><?=$key?></td>
              <td><?=$val?></td>
            </tr>

          <?php 
              $i++; 
            } 
          ?>
        </table>

      </div>
  
  </body>
</html>