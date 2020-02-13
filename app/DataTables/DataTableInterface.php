<?php

namespace App\DataTables;

interface DataTableInterface
{
    public function setTableId();

    public function setSourceData();

    public function columnsTable();

    public function getCustomOptionsParameters();

    public function getMethodAjax();

    public function getUrlAjax();
}
