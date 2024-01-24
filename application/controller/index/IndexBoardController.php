<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRequestDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardResponseDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';

    $searchWord = filter_var(strip_tags($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS);
    $dateValue = $_GET['dateValue'];

    $numPerPage = 5;
    $pageNow = $_GET['page'] ? filter_var(strip_tags(intval($_GET['page'])), FILTER_SANITIZE_SPECIAL_CHARS) : 1;
    $blockNow = floor(($pageNow - 1) / $numPerPage) * $numPerPage;
    $startIndexPerPage = ($pageNow - 1) * $numPerPage;
    $sort = filter_var(strip_tags($_GET['sort']), FILTER_SANITIZE_SPECIAL_CHARS);

    $boardDto = new BoardRequestDto($searchWord, $dateValue, $numPerPage, $pageNow, $blockNow, $startIndexPerPage, $sort);
    $boardRepository = new BoardRepository();
    $boardResponseDto = $boardRepository->pagenate($boardDto);
    
    $totalCnt = $boardResponseDto->getTotalCnt();
    $totalPages = ceil($totalCnt / $numPerPage);
    $result = $boardResponseDto->getResult();
?>