<?php
require 'db_conn.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To do list</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <!-- the main div section where the text input and add buton -->
    <div class="main-section">
        <div class="add-section">
            <form action="app/add.php" method="POST" autocomplete="off">
            <?php if (isset($_GET['mess']) && $_GET['mess']== 'error_title'){ ?>
                <input type="text" 
                       name="title" 
                       style="border-color: red;"
                       placeholder="Required Field! ">
                       <input type="date" 
                       name="deadLine">                                  
                <button type="submit">add </button> 
                <p class="todo-item">please inter a title! </p> 
                <?php } else if (isset($_GET['mess']) && $_GET['mess']== 'error_date') { ?>
                    <input type="text" 
                       name="title" 
                       placeholder="Please inter the Dead Line too">
                <input type="date" 
                       name="deadLine"
                       style="border-color: red;">
                <button type="submit">add </button> 
                 
                <p  class="todo-item"> please inter a dead line! </p>                     
                
                <?php } else{ ?>
                <input type="text" 
                       name="title" 
                       placeholder="What is your next mission?">
                <input type="date" 
                       name="deadLine">            
                <button type="submit">add</button> 
                <?php } ?>      
            </form>
        </div>
        
        <!--HERE COMES THE  SORT BUTTON AND FUNCTION--> 
        <div class="sort-section">
            <form method="POST" autocomplete="off">
                <button type="submit" name="sortButton" >sort by date </button>
                <?php 
                     $todos=$conn->query("SELECT *FROM activities ORDER BY date_time desc");
                    if(isset($_POST['sortButton']))
                    {
                    $todos=$conn->query("SELECT *FROM activities ORDER BY date_time");
                    }
                 ?> 
            </form>
        </div>
       
        
        
        <!-- THE TO DO SECTION  -->              
        <div class="show-todo-section">
            <?php
            if ($todos->rowCount()===0){
            ?>
                <div class="todo-item">
                    <h2>hey there! Lets go Productive. insert your activities in the text box to start</h2>
                    
                </div>
            <?php } ?>
            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <span id="<?php echo $todo['id']; ?>"
                          class="remove-to-do">x</span>
                          <!--CHECKK BOX FUNCTIONS  -->
                    <?php if($todo['checked']){ ?> 
                        <input type="checkbox"
                               class="check-box"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               checked />
                               
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php }else { ?>
                        <input type="checkbox"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <!-- dATE AND STATUS   -->
                    <small>created: <?php echo $todo['date_time'] ?></small> 
                    <small>Dead line: <?php echo $todo['dead_line'] ?></small> 
                    <small>status:
                         <?php 
                            if($todo['checked']==='0')
                            {
                                echo " Open";
                            }
                            if($todo['checked']==='1')
                            {
                                echo " Closed";
                               
                            } 
                            
                         ?>
                    </small> 
                </div>
            <?php } ?>

        </div>
    </div>
    

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                
                $.post("app/remove.php", 
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');
                
                $.post('app/check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });
        });
    </script>
</body>
</html>