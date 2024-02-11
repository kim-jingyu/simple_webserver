function deleteFunc(commentId, boardId) {
    location.href = "/application/controller/comment/CommentDeleteController.php?commentId=" + commentId + "&boardId=" + boardId;
}