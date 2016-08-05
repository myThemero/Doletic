<?php

interface DBFillInterface {

    public function fetchData();

    public function formatFetchedData();

    public function getFetchedData();

    public function getFormattedData();

    public function printFormattedData();

}