function RemoveField(id) {
    $("#question" + id).css("background", "#F00");
    JsHttpRequest.query('modules/lenta/questions-JSReq.php', {'action': 'remove', 'id': id}, function (result, errors) {
        if (result) { fremove(id); }
    }, true);
}

function fremove(id) {
    $("#question" + id).slideUp(333);
    setTimeout(function () { $("#question" + id).remove(); }, 333);
}

function AddField(lenta, pid) {
    JsHttpRequest.query('modules/lenta/questions-JSReq.php', {'action': 'add', 'pid': pid, 'lenta': lenta}, function (result, errors) {
        if (result) { $("#Questions").append(result["content"]); }
    }, true);
}