<?php
/**
 * Table Definition for Items_Reviews
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
 * Table Definition for Items_Reviews
 *
 * @category GeebyDeeby
 * @package  Db_Table
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/demiankatz/Geeby-Deeby Main Site
 */
class ItemsReviews extends Gateway
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('Items_Reviews');
    }

    /**
     * Get a list of reviews for the specified item.
     *
     * @var int    $itemID   Item ID
     * @var string $approved 'y' to get only approved items, 'n' for only unapproved
     * items, null for all items
     *
     * @return mixed
     */
    public function getReviewsForItem($itemID, $approved = 'y')
    {
        $callback = function ($select) use ($itemID, $approved) {
            $select->order('Username');
            $select->join(
                array('u' => 'Users'),
                'Items_Reviews.User_ID = u.User_ID'
            );
            if (null !== $approved) {
                $select->where->equalTo('Approved', $approved);
            }
            $select->where->equalTo('Item_ID', $itemID);
        };
        return $this->select($callback);
    }

    /**
     * Get a list of items reviewed by the specified user.
     *
     * @var int    $userID   User ID
     * @var string $approved 'y' to get only approved items, 'n' for only unapproved
     * items, null for all items
     */
    public function getReviewsByUser($userID, $approved = 'y')
    {
        $callback = function ($select) use ($userID, $approved) {
            $select->join(
                array('i' => 'Items'),
                'Items_Reviews.Item_ID = i.Item_ID'
            );
            $select->join(
                array('iis' => 'Items_In_Series'),
                'i.Item_ID = iis.Item_ID'
            );
            $select->join(
                array('s' => 'Series'),
                'iis.Series_ID = s.Series_ID'
            );
            $select->order(
                array('Series_Name', 's.Series_ID', 'iis.Position', 'Item_Name')
            );
            if (null !== $approved) {
                $select->where->equalTo('Approved', $approved);
            }
            $select->where->equalTo('User_ID', $userID);
        };
        return $this->select($callback);
    }
}