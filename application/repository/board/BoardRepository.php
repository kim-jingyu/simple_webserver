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
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT * FROM board WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
            } catch (Exception $e) {
                throw new Exception("FindAllById At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function updateId($newId, $oldId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET user_id = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newId, $oldId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Update UserId At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function pagenate(BoardRequestDto $boardRequestDto) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $cntSql = "SELECT count(*) AS cnt FROM board WHERE title LIKE ? AND date_value LIKE ?";
                $stmt = $conn->prepare($cntSql);
                $searchWord = $boardRequestDto->getSearchWord();
                $searchWord = '%'.$searchWord.'%';
                $dateValue = $boardRequestDto->getDateValue();
                $dateValue = '%'.$dateValue.'%';
                $stmt->bind_param("ss", $searchWord, $dateValue);
                $stmt->execute();
                $result = $stmt->get_result();
                $totalCnt = $result->fetch_assoc()['cnt'];

                $selectSql = "SELECT * FROM board WHERE title LIKE ? AND date_value LIKE ?";
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
                $selectSql .= " limit ?, ?";

                $stmt = $conn->prepare($selectSql);
                $startIndexPerPage = $boardRequestDto->getStartIndexPerPage();
                $numPerPage = $boardRequestDto->getNumPerPage();
                $stmt->bind_param("ssii", $searchWord, $dateValue, $startIndexPerPage, $numPerPage);
                $stmt->execute();
                $result = $stmt->get_result();

                $boardResponseDto = new BoardResponseDto($totalCnt, $result);
                return $boardResponseDto;
            } catch (Exception $e) {
                throw new Exception("Pagnation At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }
    
                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function write(BoardWriteRequest $boardWriteRequest) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "INSERT INTO board (title, body, user_id, date_value, file_name, views, likes) VALUES (?, ?, ?, ?, ?, 0, 0)";
                $stmt = $conn->prepare($sql);
                $title = $boardWriteRequest->getTitle();
                $body = $boardWriteRequest->getBody();
                $userId = $boardWriteRequest->getUserId();
                $today = $boardWriteRequest->getToday();
                $storedFileName = $boardWriteRequest->getStoredFileName();
                $stmt->bind_param("sssss", $title, $body, $userId, $today, $storedFileName);
                $stmt->execute();

                $boardId = $conn->insert_id;
                return $boardId;
            } catch (Exception $e) {
                throw new Exception("Write At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function fix(BoardFixRequest $boardFixRequest) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET title = ?, body = ?, user_id = ?, date_value = ?, file_name = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $title = $boardFixRequest->getTitle();
                $body = $boardFixRequest->getBody();
                $userId = $boardFixRequest->getUserId();
                $today = $boardFixRequest->getToday();
                $storedFileName = $boardFixRequest->getStoredFileName();
                $boardId = $boardFixRequest->getBoardId();
                $stmt->bind_param("sssssi", $title, $body, $userId, $today, $storedFileName, $boardId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Fix At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function like($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET likes = likes + 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
                $stmt->get_result();
            } catch (Exception $e) {
                throw new Exception("Like At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function view($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET views = views + 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
            } catch (Exception $e) {
                throw new Exception("View At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function delete($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "delete from board where id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Delete At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function findWithComments($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "SELECT * FROM board INNER JOIN comment ON board.id = comment.board_id WHERE board.id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
            } catch (Exception $e) {
                throw new Exception("findWithComments At Board - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }
    }
?>