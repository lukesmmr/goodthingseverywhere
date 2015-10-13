/* auxilliary functions */
function randomInRange(start,end){return Math.floor(Math.random() * (end - start + 1) + start);}
function randomFrom(array) {return array[Math.floor(Math.random() * array.length)];}
function getNow(){
    var data = new Date();
    var hr = data.getHours().toString();
    var min = data.getMinutes().toString();
    var ss = data.getSeconds().toString();
    var ms = data.getMilliseconds().toString();
    if(hr.length<2){hr = "0"+hr;}
    if(min.length<2){min = "0"+min;}
    if(ss.length<2){ss = "0"+ss;}
    if(ms.length<2){ms = "0"+ms;}
    return hr+":"+min+":"+ss+":"+ms;
}

function timeAgo(timestamp) {
    var secs = ((new Date()).getTime() / 1000) - timestamp;
    Math.floor(secs);
    var minutes = secs / 60;
    secs = Math.floor(secs % 60);
    if (minutes < 1) {
        return secs + (secs > 1 ? ' seconds ago' : ' second ago');
    }
    var hours = minutes / 60;
    minutes = Math.floor(minutes % 60);
    if (hours < 1) {
        return minutes + (minutes > 1 ? ' minutes ago' : ' minute ago');
    }
    var days = hours / 24;
    hours = Math.floor(hours % 24);
    if (days < 1) {
        return hours + (hours > 1 ? ' hours ago' : ' hour ago');
    }
    var weeks = days / 7;
    days = Math.floor(days % 7);
    if (weeks < 1) {
        return days + (days > 1 ? ' days ago' : ' day ago');
    }
    var months = weeks / 4.35;
    weeks = Math.floor(weeks % 4.35);
    if (months < 1) {
        return weeks + (weeks > 1 ? ' weeks ago' : ' week ago');
    }
    var years = months / 12;
    months = Math.floor(months % 12);
    if (years < 1) {
        return months + (months > 1 ? ' months ago' : ' month ago');
    }
    years = Math.floor(years);
    return years + (years > 1 ? ' years ago' : ' years ago');
}