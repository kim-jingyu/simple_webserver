<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRequestDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardResponseDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/board/BoardService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/IndexBoardViewResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/IndexBoardFixResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    class BoardController {
        public function __construct() {
        }

        public function getIndexBoard() {
            $searchWord = filter_var(strip_tags($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS);
            $dateValue = filter_var(strip_tags($_GET['dateValue']), FILTER_SANITIZE_SPECIAL_CHARS);

            $numPerPage = 5;
            
            $pageNow = $_GET['page'] ? filter_var(strip_tags(intval($_GET['page'])), FILTER_SANITIZE_SPECIAL_CHARS) : 1;
            $blockNow = floor(($pageNow - 1) / $numPerPage) * $numPerPage;
            $startIndexPerPage = ($pageNow - 1) * $numPerPage;
            $sort = filter_var(strip_tags($_GET['sort']), FILTER_SANITIZE_SPECIAL_CHARS);

            $boardDto = new BoardRequestDto($searchWord, $dateValue, $numPerPage, $startIndexPerPage, $sort);

            try {
                $boardService = new BoardService();
                $indexBoardResponse = $boardService->getIndexBoard($boardDto);
                return $indexBoardResponse;
            } catch (Exception $e) {
                echo "<script>alert('게시판을 가져오는 도중에 문제가 발생했습니다!');</script>";
            }
            
        }

        public function getIndexBoardFix() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

            try {
                $boardService = new BoardService();
                $indexBoardFixResponse = $boardService->getIndexBoardFix($boardId);
                return $indexBoardFixResponse;
            } catch (Exception $e) {
                echo "<script>alert('수정폼을 가져오는 도중에 문제가 발생했습니다!');</script>";
            }
        }

        public function getIndexBoardView() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

            try {
                $boardService = new BoardService();
                $indexBoardViewResponse = $boardService->getIndexBoardView($boardId);
                return $indexBoardViewResponse;
            } catch (Exception $e) {
                "<script>alert('게시글을 가져오는 도중에 문제가 발생했습니다!');</script>";
            }
        }

        public function writeIndexBoard($title, $body, $userId, $file) {
            $today = date("Y-m-d");

            try {
                $boardService = new BoardService();
                $boardId = $boardService->write($title, $body, $userId, $today, $file);
                return $boardId;
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function fixIndexBoard($boardId, $title, $body, $userId, $file) {
            $today = date("Y-m-d");

            try {
                $boardService = new BoardService();
                $boardService->fix($boardId, $title, $body, $userId, $today, $file);
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function getComment($boardId) {
            try {
                $boardService = new BoardService();
                $row = $boardService->getComment($boardId);
                return $row;
            } catch (Exception $e) {
                echo "<script>alert('댓글을 가져오는 도중에 문제가 발생했습니다!');</script>";
            }
        }
    }    
?>