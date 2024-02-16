<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardResponseDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRequestDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardFixRequest.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardWriteRequest.php';

    class BoardRepository {
        public function __construct() {
        }

        public function findAllById($boardId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT * FROM board WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":id", $boardId, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }

                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function findUserIdById($conn, $boardId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT user_id FROM board WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":id", $boardId, PDO::PARAM_INT);
                $stmt->execute();
                $userId = $stmt->fetchColumn();
                return $userId;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function findFileNameById($conn, $boardId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT file_name FROM board WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":id", $boardId, PDO::PARAM_INT);
                $stmt->execute();
                $fileName = $stmt->fetchColumn();
                return $fileName;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function updateId($conn, $newId, $oldId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET user_id = :newId WHERE user_id = :oldId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":newId", $newId);
                $stmt->bindValue(":oldId", $oldId);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function pagenate(BoardRequestDto $boardRequestDto) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $cntSql = "SELECT count(*) AS cnt FROM board WHERE title LIKE :searchWord AND date_value LIKE :dateValue";
                $stmt = $conn->prepare($cntSql);
                $searchWord = $boardRequestDto->getSearchWord();
                $searchWord = '%'.$searchWord.'%';
                $dateValue = $boardRequestDto->getDateValue();
                $dateValue = '%'.$dateValue.'%';
                $stmt->bindValue(":searchWord", $searchWord);
                $stmt->bindValue(":dateValue", $dateValue);
                $stmt->execute();
                $totalCnt = $result->fetchColumn();

                $selectSql = "SELECT * FROM board WHERE title LIKE :searchWord AND date_value LIKE :dateValue";
                if ($boardRequestDto->getSort() != null) {
                    $sort = $boardRequestDto->getSort();
                    if ($sort == 'author') {
                        $selectSql .=  " order by user_id desc";
                    } else if ($sort == 'date') {
                        $selectSql .= " order by date_value desc";
                    } else if ($sort == 'views') {
                        $selectSql .= " order by views desc";
                    } else if ($sort == 'likes') {
                        $selectSql .= " order by likes desc";
                    } else {
                        $selectSql .= " order by id desc";
                    }
                }
                $selectSql .= " limit :startIndexPerPage, :numPerPage";

                $stmt = $conn->prepare($selectSql);
                $startIndexPerPage = $boardRequestDto->getStartIndexPerPage();
                $numPerPage = $boardRequestDto->getNumPerPage();
                $stmt->bindValue(":searchWord", $searchWord);
                $stmt->bindValue(":dateValue", $dateValue);
                $stmt->bindValue(":startIndexPerPage", $startIndexPerPage);
                $stmt->bindValue(":numPerPage", $numPerPage);
                $stmt->execute();
                $boardData = $stmt->fetch();

                $boardResponseDto = new BoardResponseDto($totalCnt, $boardData);
                return $boardResponseDto;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function write($conn, BoardWriteRequest $boardWriteRequest) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "INSERT INTO board (title, body, user_id, date_value, file_name, views, likes) VALUES (:title, :body, :userId, :today, :storedFileName, 0, 0)";
                $stmt = $conn->prepare($sql);
                $title = $boardWriteRequest->getTitle();
                $body = $boardWriteRequest->getBody();
                $userId = $boardWriteRequest->getUserId();
                $today = $boardWriteRequest->getToday();
                $storedFileName = $boardWriteRequest->getStoredFileName();
                $stmt->bindValue(":title", $title);
                $stmt->bindValue(":body", $body);
                $stmt->bindValue(":userId", $userId);
                $stmt->bindValue(":today", $today);
                $stmt->bindValue(":storedFileName", $storedFileName);
                $stmt->execute();

                $boardId = $conn->lastInsertId();
                return $boardId;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function fix($conn, BoardFixRequest $boardFixRequest) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET title = :title, body = :body, user_id = :userId, date_value = :today, file_name = :storedFileName WHERE id = :boardId";
                $stmt = $conn->prepare($sql);
                $title = $boardFixRequest->getTitle();
                $body = $boardFixRequest->getBody();
                $userId = $boardFixRequest->getUserId();
                $today = $boardFixRequest->getToday();
                $storedFileName = $boardFixRequest->getStoredFileName();
                $boardId = $boardFixRequest->getBoardId();
                $stmt->bindValue(":title", $title);
                $stmt->bindValue(":body", $body);
                $stmt->bindValue(":userId", $userId);
                $stmt->bindValue(":today", $today);
                $stmt->bindValue(":storedFileName", $storedFileName);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function like($conn, $boardId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET likes = likes + 1 WHERE id = :boardId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function view($conn, $boardId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET views = views + 1 WHERE id = :boardId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();
                $rowCount = $stmt->rowCount();
                return $rowCount;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function delete($conn, $boardId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "DELETE FROM board WHERE id = :boardId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function findWithComments($boardId) {
            $conn = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT comment.id AS id, comment.commenter_id AS commenter_id, comment.comment_date AS comment_date, comment.body AS body FROM board INNER JOIN comment ON board.id = comment.board_id WHERE board.id = :boardId";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":boardId", $boardId, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }
    }
?>