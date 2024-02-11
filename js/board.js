const modal = document.getElementById("modal");
const btnModal = document.getElementById("fix-btn");
btnModal.addEventListener("click", e => {
    modal.style.display = "flex";
})

const closeBtn = document.querySelector(".close-area");
closeBtn.addEventListener("click", e => {
    modal.style.display = "none";
})

modal.addEventListener("click", e => {
    const eventTarget = e.target;
    if (eventTarget.classList.contains("modal-overlay")) {
        modal.style.display = "none";
    }
})

window.addEventListener("keyup", e => {
    if (modal.style.display == "flex" && e.key == "Escape") {
        modal.style.display = "none";
    }
})

function deleteFunc(commentId, boardId) {
    location.href = "/application/controller/comment/CommentDeleteController.php?commentId=" + commentId + "&boardId=" + boardId;
}