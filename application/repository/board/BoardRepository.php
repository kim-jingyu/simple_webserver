<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardResponseDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRequestDto.php';

    class BoardRepository {
        public function __construct() {
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
                if ($board_id) {
                    $sql = "update board set title = ?, body = ?, user_id = ?, date_value = ?, file_name = ? where id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssi", $title, $body, $user_id, $today, $stored_file_name, $board_id);
                    $stmt->execute();
                } else {
                    $sql = "insert into board (title, body, user_id, date_value, file_name, views, likes) values (?, ?, ?, ?, ?, 0, 0)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssss", $title, $body, $user_id, $today, $stored_file_name);
                    $stmt->execute();
                }
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

        public function like($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "update board set likes = likes + 1 where id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
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
    }
?>