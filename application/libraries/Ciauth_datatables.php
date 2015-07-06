<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ciauth_datatables {

    protected $CI;

    public function __construct() {

        $this->CI = & get_instance();

        $this->CI->load->model("M_ciauth_datatables");
    }

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    public function data_output($columns, $data) {

        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[$column['dt']] = $column['formatter']($data[$i]->$column['db'], $data[$i]);
                } else if (isset($column['actions'])) {
                    $row[$column['dt']] = "<i class=\"fa fa-pencil-square-o\"></i>&nbsp;<i class=\"fa fa-print\"></i>&nbsp;<i class=\"fa fa-trash-o\"></i>&nbsp;<i class=\"fa fa-credit-card\"></i>";
                } else {
                    $value = explode('.', $columns[$j]['db']);
                    if (count($value) > 1) {
                        $row[$column['dt']] = $data[$i]->$value[1];
                    } else {
                        $row[$column['dt']] = $data[$i]->$columns[$j]['db'];
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }

    public function join($join_data) {
        if (isset($join_data['join_table']) && isset($join_data['main_table_column']) && isset($join_data['join_table_column'])) {
            $join_clause = "LEFT JOIN `" . $join_data['join_table'] . "` b ON a.`" . $join_data['main_table_column'] . "` = b.`" . $join_data['join_table_column'] . "`";
            return $join_clause;
        } else {
            return "";
        }
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    public function limit($request, $columns) {
        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }

        return $limit;
    }

    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    public function order($request, $columns) {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = $this->pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                            'ASC' :
                            'DESC';
                    $column_details = explode(".", $column['db']);
                    if (count($column_details) > 1) {
                        $orderBy[] = $column_details[0] . ".`" . $column_details[1] . "` " . $dir;
                    } else {
                        $orderBy[] = '`' . $column['db'] . '` ' . $dir;
                    }
                }
            }
            if (!empty($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }

        return $order;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    public function filter($request, $columns, &$bindings, $where_data) {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = $this->pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];

            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);

                $column = $columns[$columnIdx];

                if ($requestColumn['searchable'] == 'true') {
                    $binding = '"%' . $str . '%"';
                    $column_details = explode('.', $column['db']);
                    //print_r($column_details); echo "<br />";
                    if (count($column_details) > 1) {
                        $globalSearch[] = $column_details[0] . ".`" . $column_details[1] . "` LIKE " . $binding;
                    } else {
                        $globalSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
                    }
                }
            }
        }

        // Individual column filtering
        if (!empty($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                $str = $requestColumn['search']['value'];

                if ($requestColumn['searchable'] == 'true' &&
                        $str != '') {
                    $binding = '"%' . $str . '%"';
                    $column_details = explode('.', $column['db']);

                    if (count($column_details) > 1) {
                        $globalSearch[] = $column_details[0] . ".`" . $column_details[1] . "` LIKE " . $binding;
                    } else {
                        $globalSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
                    }
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (!empty($where_data)) {
            $count = 1;
            foreach ($where_data as $wd) {
                if ($count == 1) {
                    $where .= $wd['column'] . " " . $wd['operator'] . " '" . $wd['value'] . "'";
                } else {
                    $where .= " AND " . $wd['column'] . " " . $wd['operator'] . " '" . $wd['value'] . "'";
                }
                $count++;
            }
        }

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                    implode(' AND ', $columnSearch) :
                    $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    public function simple($request, $table, $join_data, $where_data, $primaryKey, $columns) {
        $bindings = array();

        // Build the SQL query string from the request
        $limit = $this->limit($request, $columns);
        $order = $this->order($request, $columns);
        $where = $this->filter($request, $columns, $bindings, $where_data);
        $join = $this->join($join_data);

        $sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(",", $this->pluck($columns, 'db')) . " FROM `" . $table . "` a " . $join . " " . $where . " " . $order . " " . $limit;

        if (!$data = $this->CI->M_ciauth_datatables->get_data($sql)) {
            $data = array();
        }

        // Data set length after filtering
        $resFilterLength = $this->CI->M_ciauth_datatables->get_data("SELECT FOUND_ROWS() AS rows");

        $recordsFiltered = $resFilterLength[0]->rows;

        // Total data set length
        $resTotalLength = $this->CI->M_ciauth_datatables->get_data("SELECT COUNT(`{$primaryKey}`) AS count FROM `" . $table . "`");

        $recordsTotal = $resTotalLength[0]->count;

        $return = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data)
        );

        /*
         * Output
         */
        return $return;
    }

    /**
     * The difference between this method and the `simple` one, is that you can
     * apply additional `where` conditions to the SQL queries. These can be in
     * one of two forms:
     *
     * * 'Result condition' - This is applied to the result set, but not the
     *   overall paging information query - i.e. it will not effect the number
     *   of records that a user sees they can have access to. This should be
     *   used when you want apply a filtering condition that the user has sent.
     * * 'All condition' - This is applied to all queries that are made and
     *   reduces the number of records that the user can access. This should be
     *   used in conditions where you don't want the user to ever have access to
     *   particular records (for example, restricting by a login id).
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  string $whereResult WHERE condition to apply to the result set
     *  @param  string $whereAll WHERE condition to apply to all queries
     *  @return array          Server-side processing response array
     */
    public function complex($request, $table, $primaryKey, $columns, $whereResult = null, $whereAll = null) {
        $bindings = array();
        $localWhereResult = array();
        $localWhereAll = array();
        $whereAllSql = '';

        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        $whereResult = self::_flatten($whereResult);
        $whereAll = self::_flatten($whereAll);

        if ($whereResult) {
            $where = $where ?
                    $where . ' AND ' . $whereResult :
                    'WHERE ' . $whereResult;
        }

        if ($whereAll) {
            $where = $where ?
                    $where . ' AND ' . $whereAll :
                    'WHERE ' . $whereAll;

            $whereAllSql = 'WHERE ' . $whereAll;
        }

        // Main query to actually get the data
        $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS `" . implode("`, `", self::pluck($columns, 'db')) . "`
			 FROM `$table`
			 $where
			 $order
			 $limit"
        );

        // Data set length after filtering
        $resFilterLength = self::sql_exec($db, "SELECT FOUND_ROWS()"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($db, $bindings, "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` " .
                        $whereAllSql
        );
        $recordsTotal = $resTotalLength[0][0];

        /*
         * Output
         */
        return array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columns, $data)
        );
    }

    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */

    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    public function fatal($msg) {
        echo json_encode(array(
            "error" => $msg
        ));

        exit(0);
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    public function pluck($a, $prop) {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            if (!empty($a[$i][$prop])) {
                if ($prop == 'db') {
                    $column_details = explode('.', $a[$i][$prop]);
                    if (count($column_details) > 1) {
                        $out[] = $column_details[0] . ".`" . $column_details[1] . "`";
                    } else {
                        $out[] = "`" . $a[$i][$prop] . "`";
                    }
                } else {
                    $out[] = $a[$i][$prop];
                }
            }
        }

        return $out;
    }

    /**
     * Return a string from an array or a string
     *
     * @param  array|string $a Array to join
     * @param  string $join Glue for the concatenation
     * @return string Joined string
     */
    public function _flatten($a, $join = ' AND ') {
        if (!$a) {
            return '';
        } else if ($a && is_array($a)) {
            return implode($join, $a);
        }
        return $a;
    }

}
