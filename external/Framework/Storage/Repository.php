<?php

namespace Framework\Storage;

interface Repository
{
    public function getAll();
    public function get($id);
    public function create($entity);
    public function update($entity);
    public function remove($entity);
}