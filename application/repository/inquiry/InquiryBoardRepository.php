<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/inquiry/InquiryPagenateResponse.php';

    class InquiryBoardRepository {
        public function __construct() {
        }

        public function pagenate($conn, InquiryBoardRequest $inquiryBoardRequest) {
            $stmt = null;
            try {   
                $totalCountSql = "SELECT count(*) AS cnt FROM inquiry_board WHERE title LIKE :searchWord AND date_value LIKE :dateValue";
                $stmt = $conn->prepare($totalCountSql);
                $searchWord = $inquiryBoardRequest->getSearchWord();
                $searchWord = '%'.$searchWord.'%';
                $dateValue = $inquiryBoardRequest->getDateValue();
                $dateValue = '%'.$dateValue.'%';
                $stmt->bindValue(":searchWord", $searchWord);
                $stmt->bindValue(":dateValue", $dateValue);
                $stmt->execute();
                $totalCnt = $result->fetchColumn();
                
                $selectSql = "SELECT * FROM inquiry_board WHERE title LIKE :searchWord AND date_value LIKE :dateValue";
                
                if (isset($_GET['sort'])) {
                    $sort = $inquiryBoardRequest->getSort();
                    if ($sort == 'author') {
                        $select_sql .= " order by writer_name desc";
                    } else if ($sort == 'date') {
                        $select_sql .= " order by date_value desc";
                    } else {
                        $select_sql .= " order by id asc";
                    }
                }
                $selectSql .= " LIMIT :startIndexPerPage, :numPerPage";

                $startIdxPerPage = $inquiryBoardRequest->getStartIdxPerPage();
                $numPerPage = $inquiryBoardRequest->getNumPerPage();
                
                $stmt = $conn->prepare($selectSql);
                $stmt->bindValue(":searchWord", $searchWord);
                $stmt->bindValue(":dateValue", $dateValue);
                $stmt->bindValue(":startIndexPerPage", $startIndexPerPage, PDO::PARAM_INT);
                $stmt->bindValue(":numPerPage", $numPerPage, PDO::PARAM_INT);
                $stmt->execute();
                $boardData = $stmt->fetch();

                $inquiryPagenateResponse = new InquiryPagenateResponse($totalCnt, $boardData);
                return $inquiryPagenateResponse;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function findById($conn, $boardId) {
            $stmt = null;
            try {
                $sql = "SELECT * FROM inquiry_board WHERE id = :id";
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
            }
        }

        public function findWriterNameById($conn, $boardId) {
            $stmt = null;
            try {
                $sql = "SELECT writer_name FROM inquiry_board WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":id", $boardId, PDO::PARAM_INT);
                $stmt->execute();
                $name = $stmt->fetchColumn();
                return $name;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function findPwByWriterName($conn, $writerName) {
            $stmt = null;
            try {
                $sql = "SELECT writer_pw FROM inquiry_board WHERE writer_name= :writerName";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":writerName", $writerName);
                $stmt->execute();
                $pw = $stmt->fetchColumn();
                return $pw;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function update($conn, InquiryBoardUpdateRequest $inquiryBoardUpdateRequest) {
            $stmt = null;
            try {
                $sql = "UPDATE inquiry_board SET title = :title, body = :body, date_value = :today WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $title = $inquiryBoardUpdateRequest->getTitle();
                $body = $inquiryBoardUpdateRequest->getBody();
                $today = $inquiryBoardUpdateRequest->getToday();
                $boardId = $inquiryBoardUpdateRequest->getBoardId();
                $stmt->bindValue(":title", $title);
                $stmt->bindValue(":body", $body);
                $stmt->bindValue(":today", $today);
                $stmt->bindValue(":id", $boardId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function save($conn, InquiryBoardWriteRequest $inquiryBoardWriteRequest) {
            $stmt = null;
            try {
                $sql = "INSERT INTO inquiry_board (title, body, writer_name, writer_pw, date_value) VALUES (:title, :body, :writerName, :writerPw, :today)";
                $stmt = $conn->prepare($sql);
                $title = $inquiryBoardWriteRequest->getTitle();
                $body = $inquiryBoardWriteRequest->getBody();
                $writerName = $inquiryBoardWriteRequest->getWriterName();
                $writerPw = $inquiryBoardWriteRequest->getWriterPw();
                $today = $inquiryBoardWriteRequest->getToday();
                $stmt->bindValue(":title", $title);
                $stmt->bindValue(":body", $body);
                $stmt->bindValue(":writerName", $writerName);
                $stmt->bindValue(":writerPw", $writerPw);
                $stmt->bindValue(":today", $today);
                $stmt->execute();

                $boardId = $conn->lastInsertId();
                return $boardId;
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }

        public function deleteById($conn, $boardId) {
            $stmt = null;
            try {
                $sql = "DELETE FROM inquiry_board WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(":id", $boardId, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                throw $e;
            } finally {
                if ($stmt != null) {
                    $stmt = null;
                }
            }
        }
    }
?>