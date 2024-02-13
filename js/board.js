const modals = document.getElementsByClassName("modals");
const fixBtns = document.getElementsByClassName("fix-comment-btn");
const closeBtns = document.getElementsByClassName("close-area");
var funcs = [];

function modal(num) {
    return function () {
        fixBtns[num].onclick = function () {
            modals[num].style.display = "flex";
        };

        closeBtns[num].onclick = function () {
            modals[num].style.display = "none";
        };

        window.addEventListener("keyup", e => {
            if (modals[num].style.display == "flex" && e.key == "Escape") {
                modals[num].style.display = "none";
            }
        })
    }
}

for (var i = 0; i < fixBtns.length; i++) {
    funcs[i] = modal(i);
}

for (var i = 0; i < fixBtns.length; i++) {
    funcs[i]();
}

window.onclick = function (event) {
    if (event.target.className == "modals") {
        event.target.style.display = "none";
    }
}

function deleteFunc(commentId, boardId) {
    location.href = "/application/controller/comment/CommentDeleteController.php?commentId=" + commentId + "&boardId=" + boardId;
}