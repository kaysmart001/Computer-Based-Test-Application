
    var ans_arr = {};
        
    if (localStorage.getItem('ans_arr')) {
        ans_arr = JSON.parse(localStorage.getItem('ans_arr'));
        } else {
        ans_arr = {};
    }

    var err_ans_arr = {};
    var err_ans_arr_p = {};

    if (localStorage.getItem('err_ans_arr')) {
        err_ans_arr = JSON.parse(localStorage.getItem('err_ans_arr'));
        err_ans_arr_p = JSON.parse(localStorage.getItem('err_ans_arr_p'));
    } else {
        err_ans_arr = {};
        err_ans_arr_p = {};
    }

    function sectoTime(secc) {
        var timer = secc, minutes, seconds;

            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            var timm = minutes + ":" + seconds;
            return timm;         
    }

    function timetoSec(tim){
        var a = tim.split(':'); // split it at the colons

        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var seconds = ((+a[0]) * 60 + (+a[1])); 
        return seconds;
    }
     
    function usedtime(){
     //       var qctime =  $('#time span').text();
        //var qctime = document.getElementById("timespent").value;
        var qctime = $('#time').text();
        var qctimeSec = timetoSec(qctime);
         //alert(qctimeSec);
         
        var ttime = document.getElementById("sum_time").value;
        var ttimeSec = timetoSec(ttime);
        //alert(ttimeSec);
        var usedtime = ttimeSec - qctimeSec;
        //alert(usedtime);

        var usedtimeformat = sectoTime(usedtime);
         //alert(usedtimeformat);
        return usedtimeformat;
    }
    
    var usedtimeformat  = usedtime();
    
    //    setInterval(function () {
    //    var usedtimeformat2  = usedtime();    
    //   // var loc_time = localStorage.setItem("loctime", usedtimeformat2);
    //        }, 1000);

    $("input[name='choice']").click(function () {

        var queNo = $(this).data('value');
        var nextqueNo = $(this).data('next');
        var nextq = $(this).siblings("input[name='nextq']").val();

        // var TimeInterval for Time Up to Check Server
        var TimeInterval;

        //alert('#'+nextucid);
        var formID = $('#form' + queNo);
        //alert(formID);

        var usedtimeformat  = usedtime();
        //alert(usedtimeformat);

        var assid = document.getElementById("assid").value;

        $(formID).append('<input type="hidden" name="usedtime" value="' + usedtimeformat + '" />');
        $(formID).append('<input type="hidden" name="assid" value="' + assid + '" />');

        var ucid = $(this).siblings("input[name='ucid']").val();
        ans_arr[ucid] = $(this).val();
        var val = $(this).val();

        // Put the object into storage
        localStorage.setItem('ans_arr', JSON.stringify(ans_arr));

        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: $(formID).serialize(),
            //data: { id: $(this).data('value') },
            success: function (msg) {
                if (msg.indexOf("successfully") > -1) {
                    $('#p' + queNo).removeClass("btn-danger");
                    $('#p' + queNo).addClass("btn-primary");
                    $('#respond' + queNo).html(msg);
                    setTimeout(function () { $('#pques a[href="#' + nextq + '"]').tab('show');   }, 1000);
                    //$(this).next().tab('show');
                        savererror();
                } else {
                    err_ans_arr[ucid] = val;
                    localStorage.setItem('err_ans_arr', JSON.stringify(err_ans_arr));

                    err_ans_arr_p[queNo] = val; //KEEP PAGENATION ID
                    localStorage.setItem('err_ans_arr_p', JSON.stringify(err_ans_arr_p));

                    $('#p' + queNo).removeClass("btn-primary");
                    $('#p' + queNo).addClass("btn-danger");
                    $('#respond' + queNo).html('<div class="alert2 alert-danger">Choice can NOT be submitted. Continue with your work</div>');       
                    setTimeout(function () { $('#pques a[href="#' + nextq + '"]').tab('show');   }, 1000);
                }
            },
            error: function (exception) {              
                            
                err_ans_arr[ucid] = val;
                localStorage.setItem('err_ans_arr', JSON.stringify(err_ans_arr));

                err_ans_arr_p[queNo] = val; //KEEP PAGENATION ID
                localStorage.setItem('err_ans_arr_p', JSON.stringify(err_ans_arr_p));

                $('#p' + queNo).removeClass("btn-primary");
                $('#p' + queNo).addClass("btn-danger");
                $('#respond' + queNo).html('<div class="alert2 alert-danger">Choice can NOT be submited now. Continue with your work</div>');
          
                setTimeout(function () {
                            $('#pques a[href="#' + nextq + '"]').tab('show');
                            }, 1000);  
            }
        });
    });
    
    function savererror(){
           if (localStorage.getItem('err_ans_arr')){ //store error
               err_ans_arr = JSON.parse(localStorage.getItem('err_ans_arr'));
               $.ajax({
                   type: "POST",
                   url: "_inc/process.php",
                   data: {answers: err_ans_arr},
                   //dataType: "json", 
                   success: function (data) {
                       //$('#respond'+queNo).html(msg);
                       localStorage.removeItem('err_ans_arr', JSON.stringify(err_ans_arr));

                       for (var index in err_ans_arr_p) {
                           ///alert(index);
                           $('#p' + index).removeClass("btn-danger");
                           $('#p' + index).addClass("btn-primary");
                           $('#respond' + index).html('<div class="alert2 alert-success">Your Choice has been successfully submitted to the DataBase.</div>');
                       }
                       localStorage.removeItem('err_ans_arr_p', JSON.stringify(err_ans_arr));

                       saveloading();
                   },
                   error: function (exception) {
                       alert('error');
                   }
               });
           } else {
               err_ans_arr = {};
           }
       }
       
    function saveexam(){
        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: {answers: ans_arr},
            //dataType: "json", 
            success: function (data) {
                for (var index in err_ans_arr_p) {
                    //alert(index);
                    $('#p' + index).removeClass("btn-danger");
                    $('#p' + index).addClass("btn-primary");
                    $('#respond' + index).html('<div class="alert2 alert-success">Your Choice has been successfully submitted to the DataBase.</div>');
                }
                localStorage.removeItem('err_ans_arr_p', JSON.stringify(err_ans_arr));
                saveloading();
            },
            error: function (exception) {
                //alert('error connecting to server');
            }
        });
    };

    function savetime(){           
        var sum_time = usedtime();
        var userid = document.getElementById("userid").value;
        var assid = document.getElementById("assid").value;

        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data:  {savetime: 'savetime', user_id: userid, assid: assid, sum_time: sum_time},
            //data: { id: $(this).data('value') },
            success: function (msg) {
                $('#timeoutModal').modal({backdrop: 'static', keyboard: false});
                $('#duration').html(msg);
                setTimeout(function () { window.location.replace('logout.php'); }, 100);
            }
        });
    }; 
     
    $('.saveexam').on('click', function () {
        saveexam();
       
        });

    $('#logout').on('click', function () {
        //saveexam();
        savetime();
        // 
      });

    $('#calculator_btn').on('click', function () {
                $('#calculator_modal').modal('show');
      });
      
    var result = document.getElementById("result").value;
    function startTimer(duration, display, display2) {
            var timer = duration, minutes, seconds;
            var time = $('#sum_time').val();
            var time = timetoSec(time);
            var waiting = 1000;
            
            var alerttime = (((time * 25) / 100));
            var redalerttime = (((time * 10) / 100));
           // alert(time);
            //alert(redalerttime);
            var loc_time;
            
             TimeInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
       
                loc_time = localStorage.setItem("loctime", minutes + ":" + seconds);

                display.textContent = minutes + ":" + seconds;
                display2.value = minutes + ":" + seconds;
                //
                if (timer > 0) {
                    if (timer < alerttime) {
                        $('#time').addClass("blink_me");
                        if (timer < redalerttime) {
                            $('#time').addClass("red");
                        }
                    }
                    --timer;
                } else {
                    var endmsg = '<i class="fa fa-clock-o fa-fw"> </i> Your Time is Up';
                    checkServerEndExam(endmsg);
                    
                }
            }, waiting);
            }

            window.onload = function () {
                if (localStorage.getItem('loctime')) {
                    var usedtimeformat = localStorage.getItem('loctime');
                    var time = timetoSec(usedtimeformat);
                }else{
                var time = $('#time').data('value');
                }//alert(time);
                
                var timeLength = time,
                        //var fiveMinutes = 60 * 10,
                        display = document.querySelector('#time');
                display2 = document.querySelector('#timespent');
                startTimer(timeLength, display, display2);
            };

            $(document).ready(function ()
            {
                $("#passport").error(function () {
                    $(this).attr('src', '../_res/img/passport/default.jpg');
                });
            });

    function checkServerEndExam(endmsg){
         $('#endmsg').html(endmsg);
     
         $('#timeoutModal').modal({backdrop: 'static', keyboard: false});                

         $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: $('#sumform').serialize(),
            success: function (msg) {
                if (msg.indexOf("Unable") > -1) { //unable to connect                             
                        $('#duration').html(msg);

                }else{//connected to database
                  clearInterval(TimeInterval); //stop checking server
                  savererror();
                  setTimeout(function (){ $('#serverstatus').html(msg); }, 200);                          
                  setTimeout(function (){ $('#serverstatus').html('<div class="alert2 alert-info">Saving exam...</div>'); }, 800);
                  setTimeout(function (){ window.location.replace(result); }, 1200); 
              }
            },

            error: function (exception) {
                $('#duration').html('<div id="serverstatus"><div class="alert2 alert-danger blink_me">Unable to connect to Server.</div></div>');
            }
        });
    };
    
    function servererror(){
        setTimeout(function () {
            var notify = $.notify('<strong>Saving</strong> Do not close this page...', {
            type: 'danger'
        });

            notify.update('message', '<strong>Please</strong> Question Data.');
        }, 100);
      }
    function saveloading(){
        var notify = $.notify('<strong>Saving</strong> Do not close this page...', {
            type: 'info',
            allow_dismiss: false,
            showProgressbar: false,
            timer: 500
        });

        setTimeout(function () {
            notify.update('message', '<strong>Please</strong> Question Data.');
        }, 500);

        setTimeout(function () {
            notify.update('message', '<strong>Saving</strong> Answer Data.');
        }, 1000);

        setTimeout(function () {
            $.notifyClose('top-right');
        }, 1000);
    };

    $('#endexam1').on('click', function () {
        //servererror();
        $('#myModal').modal('show');
    });
    $('#confrmEndExam').on('click', function () {
        $('#myModal').modal('hide');
        var assid = document.getElementById("assid").value;
        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: {assid: assid, endexam1: 1},
            success: function (msg) {
                  if (msg.indexOf("Unable") > -1) { //unable to connect                             
                        var endmsg = 'Waiting for server connection';
                        setInterval(function (){ checkServerEndExam(endmsg); }, 1000);                                                 
                    }else{//connected to database
                $('#showunanswerd').html(msg);
                $('#showunanswerdModal').modal({backdrop: 'static', keyboard: false});                
                }
            },
             error: function (exception) {
                        var endmsg = 'Waiting for server connection';
                        setInterval(function (){ checkServerEndExam(endmsg); }, 1000);                                                 
                    }
        });               
    });

    window.onbeforeunload = function(e){
        //    //Do some thing here
        //           // var usedtime = usedtime();
        //
        //       display = document.querySelector('#time1').textContent = '2222';
        //        $.ajax({
        //            type: "POST",
        //            url: "_inc/process.php",
        //           // data: {assid: assid, used_time: usedtime, reload: 1},
        //            success: function (msg) {
        //            }
        //        });
    };
        
    function timeout(){           
        $('#timeoutModal').modal({backdrop: 'static', keyboard: false});
        saveexam();

        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: $('#sumform').serialize(),
            //data: { id: $(this).data('value') },
            success: function (msg) {
                $('#duration').html(msg);
                setTimeout(function () {
                    window.location.replace(result);
                }, 2000);
            }
        });
    };           
                 //   $('#tabb li:eq(1) a').tab('show');


    function checklogout(){
        var userid = $("input[name='userid']").data('value');
                $.ajax({
                    type: "POST",
                    url: "../students/_inc/process.php",
                    data: {checklogout: 'checklogout', user_id: userid  },
                    success: function(data) {
                           if(data != 1){                   
                                setTimeout(function () {
                                    $('#adminlogoutModal').modal({backdrop: 'static', keyboard: false});
                                    window.location.replace('logout.php');
                                }, 2000);
                           }
                        }
                    });
                };

//setInterval(function() { checklogout(); }, 5000);   
