@extends('adminlte::page')
@section('title', 'Occupancy Planner')

@section('content_header')
    <h1>Occupancy Planner</h1>
@stop

@section('content')
    <div class="response"></div>
    <div id='calendar'></div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('fullcalendar/fullcalendar.min.css')}}" />
    <style>
        #calendar {
            width: 700px;
            margin:0 auto;
        }

        .response {
            height: 60px;
        }

        .success {
            background: #cdf3cd;
            padding: 10px 60px;
            border: #c3e6c3 1px solid;
            display: inline-block;
        }
    </style>
@stop

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{asset('fullcalendar/lib/jquery.min.js')}}"></script>
    <script src="{{asset('fullcalendar/lib/moment.min.js')}}"></script>
    <script src="{{asset('fullcalendar/fullcalendar.min.js')}}"></script>
    

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>

    $(document).ready(function () {
        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: "/planner/1",
            displayEventTime: false,
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                var title = prompt('Description:');

                if (title) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                    $.ajax({
                        url: '/planner',
                        data: 'title=' + title + '&start=' + start + '&end=' + end,
                        type: "POST",
                        success: function (data) {
                            displayMessage("Added Successfully");
                            setTimeout(function(){// wait for 5 secs(2)
                                location.reload(); // then reload the page.(3)
                            });
                        }
                    });
                    calendar.fullCalendar('renderEvent',
                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            },
                    true
                            );
                }
                calendar.fullCalendar('unselect');
            },
            eventClick: function (event) {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                    $.ajax({
                        type: "DELETE",
                        url: "planner/"+event.id,
                        success: function (response) {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                            displayMessage("Deleted Successfully");
                            setTimeout(function(){// wait for 5 secs(2)
                                location.reload(); // then reload the page.(3)
                            });
                        },
                    });
                }
            }

        });
    });

    function displayMessage(message) {
            $(".response").html("<div class='success'>"+message+"</div>");
        setInterval(function() { $(".success").fadeOut(); }, 1000);
    }
    </script>
@stop