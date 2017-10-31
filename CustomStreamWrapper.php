<?php

// A stream that just loads content over HTTP
class CustomStreamWrapper {
    protected
        $position = 0,
        $length = 0,
        $data = "";

    public function stream_open($path, $mode, $options, $opened_path) {
        $path = str_replace('custom://', 'http://', $path);
        $data = file_get_contents($path);
        return $this->setData($data);
    }

    protected function setData($data) {
        if (!$data) {
            return false;
        }

        $this->data = $data;
        $this->length = strlen($data);
        return true;
    }

    public function stream_flush() {
        return true;
    }

    public function stream_tell() {
        return $this->position;
    }

    public function stream_eof() {
        return $this->position >= $this->length;
    }

    public function stream_seek($offset, $whence) {
        switch ($whence) {
            case SEEK_SET: {
                if ($this->isValidOffset($offset)) {
                    $this->position = $offset;
                    return true;
                }
                return false;
            }

            case SEEK_CUR: {
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                }
                return false;
            }

            case SEEK_END: {
                if ($this->isValidOffset($this->position + $offset)) {
                    $this->position = $this->length + $offset;
                    return true;
                }
                return false;
            }

            default: {
                return false;
            }
        }
    }

    public function url_stat() {
        return [];
    }

    public function stream_stat() {
        return [];
    }

    public function stream_close() {
        return true;
    }

    public function stream_read($count) {
        $result = substr($this->data, $this->position, $count);
        $this->position += $count;
        return $result;
    }

    public function stream_write($inputData) {
        $inputDataLength = strlen($inputData);

        $dataLeft = substr($this->data, 0, $this->position);
        $dataRight = substr($this->data, $this->position + $inputDataLength);

        $this->data = $dataLeft . $inputData . $dataRight;

        $this->position += $inputData;
        return $inputDataLength;
    }

    protected function isValidOffset($offset) {
        return ($offset >= 0) && ($offset < $this->length);
    }
}
