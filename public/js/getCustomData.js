function relative_time(date_str) {
    if (!date_str) {return;}
    date_str = $.trim(date_str);
    date_str = date_str.replace(/\.\d\d\d+/,""); // remove the milliseconds
    date_str = date_str.replace(/-/,"/").replace(/-/,"/"); //substitute - with /
    date_str = date_str.replace(/T/," ").replace(/Z/," UTC"); //remove T and substitute Z with UTC
    date_str = date_str.replace(/([\+\-]\d\d)\:?(\d\d)/," $1$2"); // +08:00 -> +0800
    var parsed_date = new Date(date_str);
    var relative_to = (arguments.length > 1) ? arguments[1] : new Date(); //defines relative to what ..default is now
    var delta = parseInt((relative_to.getTime()-parsed_date)/1000);
    delta=(delta<2)?2:delta;
    var r = '';
    if (delta < 60) {
    r = delta + ' seconds ago';
    } else if(delta < 120) {
    r = 'a minute ago';
    } else if(delta < (45*60)) {
    r = (parseInt(delta / 60, 10)).toString() + ' minutes ago';
    } else if(delta < (2*60*60)) {
    r = 'an hour ago';
    } else if(delta < (24*60*60)) {
    r = '' + (parseInt(delta / 3600, 10)).toString() + ' hours ago';
    } else if(delta < (48*60*60)) {
    r = 'a day ago';
    } else {
    r = (parseInt(delta / 86400, 10)).toString() + ' days ago';
    }
    return 'about ' + r;
};

function secondsToHIS(seconds){
    // 2- Extract hours:
    var hours = parseInt( seconds / 3600 ); // 3,600 seconds in 1 hour
    seconds = seconds % 3600; // seconds remaining after extracting hour
    // 3- Extract minutes:
    var minutes = parseInt( seconds / 60 ); // 60 seconds in 1 minute
    // 4- Keep only seconds not extracted to minutes:
    seconds = seconds % 60;

    if (seconds < 10 && seconds >= 0) seconds = '0'+seconds.toString();
    if (minutes < 10 && minutes >= 0) minutes = '0'+minutes.toString();
    if (hours < 10 && hours >= 0) hours = '0'+hours.toString();
    return( hours+":"+minutes+":"+seconds);
}

function getUpcomingContests(){
    $.ajax({
        url: '/api/home/nextcontests',
        method: 'get',
        success: function(res){
            var contests = JSON.parse(res);
            var str = "";
            for (var index in contests){
                var contest = contests[index];
                str += "<a href='/contests/"+contest.ContestID+"' >"+contest.ContestName+"</a><br>";
                str += "<span>"+contest.ContestBegin+"<br>"+contest.ContestEnd+"</span><br><br>";
            }
            $('.card-upcomingcontest').html(str);
        },
        error: function(res){
            // $('.card-upcomingcontest').html("");
            console.log(res);
        }
    });
}

function getTopUsers(){
    $.ajax({
        url: '/api/home/topusers',
        method: 'get',
        success: function(res){
            var users = JSON.parse(res);
            var str = "";
            for (var index in users){
                var user = users[index];
                str += "<span class='topuser'><a href='/profile/"+user.id+"' >"+user.name+"</a></span> | <span>"+user.Rating+"</span><br>";
            }
            $('.card-topusers').html(str);
        },
        error: function(res){
            // $('.card-upcomingcontest').html("");
            console.log(res);
        }
    })
}

function getPosts(){
    $.ajax({
        url: '/api/home/posts',
        method: 'get',
        success: function(res){
            var posts = JSON.parse(res);
            var str = "";
            for (var index in posts){
                var post = posts[index];
                str += "<div class='post'>"
                str += "<div class='post-header'><a href='/blog/entry/"+post.PostID+"'><h3>"+post.Header+"</h3></a></div>";
                str += "<div class='post-subheader'>By "+post.name+", "+relative_time(post.CreateDate)+"</div>";
                str += "<div class='post-content'>"+post.Content+"</div>";
                str += "</div>";
            }
            $('.card-post').html(str);
        },
        error: function(res){
            // $('.card-upcomingcontest').html("");
            console.log(res);
        }
    })
}

function getAllUpcomingContests(){
    $.ajax({
        url: '/api/contests/upcoming',
        method: 'get',
        success: function(res){
            var contests = JSON.parse(res);
            var str = "";
            for (var index in contests){
                var contest = contests[index];
                var contestBegin =new Date(contest.ContestBegin);
                var contestEnd = new Date(contest.ContestEnd);
                var diff = secondsToHIS((contestEnd-contestBegin)/1000);
                
                console.log(diff);
                str += "<tr>"
                    +   "<th scope='row'><a href='/contests/"+contest.ContestID+"'>"+contest.ContestName+"</a></th>"
                    +   "<td>"+contest.name+"</td>"
                    +   "<td>"+diff+"</td>"
                    +  "</tr>";
            }
            $('#upcomingContests tbody').html(str);
        },
        error: function(res){
            console.log(res);
        }
    });
}

function getContestsHistory(){
    $.ajax({
        url: '/api/contests/history',
        method: 'get',  
        success: function(res){
            var contests = JSON.parse(res);
            var str = "";
            for (var index in contests){
                var contest = contests[index];
                var contestBegin =new Date(contest.ContestBegin);
                var contestEnd = new Date(contest.ContestEnd);
                var diff = secondsToHIS((contestEnd-contestBegin)/1000);
                
                str += "<tr>"
                    +   "<th scope='row'><a href='/contests/"+contest.ContestID+"'>"+contest.ContestName+"</a></th>"
                    +   "<td>"+contest.name+"</td>"
                    +   "<td>"+diff+"</td>"
                    +  "</tr>";
            }
            $('#contestHistory tbody').html(str);
        },
        error: function(res){
            console.log(res);
        }
    });
}
