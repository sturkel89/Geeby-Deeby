<?php
/**
 * Table Definition for Series
 *
 * PHP version 5
 *
 * Copyright (C) Demian Katz 2012.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category GeebyDeeby
 * @package  Db_Table
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/demiankatz/Geeby-Deeby Main Site
 */
namespace GeebyDeeby\Db\Table;

/**
 * Table Definition for Series
 *
 * @category GeebyDeeby
 * @package  Db_Table
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/demiankatz/Geeby-Deeby Main Site
 */
class Series extends Gateway
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Series', 'GeebyDeeby\Db\Row\Series');
    }

    /**
     * Get a list of series.
     *
     * @return mixed
     */
    public function getList()
    {
        $callback = function ($select) {
            $select->order('Series_Name');
        };
        return $this->select($callback);
    }

    /**
     * Get autocomplete suggestions.
     *
     * @param string $query The user query.
     * @param mixed  $limit Limit on returned rows (false for no limit).
     *
     * @return mixed
     */
    public function getSuggestions($query, $limit = false)
    {
        $callback = function ($select) use ($query, $limit) {
            if ($limit !== false) {
                $select->limit($limit);
            }
            $select->where->like('Series_Name', $query . '%');
            $select->order('Series_Name');
        };
        return $this->select($callback);
    }
}
