<?php
/**
 * Series controller
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
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/demiankatz/Geeby-Deeby Main Site
 */
namespace GeebyDeeby\Controller;

/**
 * Series controller
 *
 * @category GeebyDeeby
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/demiankatz/Geeby-Deeby Main Site
 */
class SeriesController extends AbstractBase
{
    /**
     * Get a view model containing a series object (or return false if missing)
     *
     * @return mixed
     */
    protected function getViewModelWithSeries()
    {
        $id = $this->params()->fromRoute('id');
        $table = $this->getDbTable('series');
        $rowObj = (null === $id) ? null : $table->getByPrimaryKey($id);
        if (!is_object($rowObj)) {
            return false;
        }
        return $this->createViewModel(
            array('series' => $rowObj->toArray())
        );
    }

    /**
     * "Show series images" page
     *
     * @return mixed
     */
    public function imagesAction()
    {
        $view = $this->getViewModelWithSeries();
        $view->images = $this->getDbTable('itemsinseries')
            ->getImagesForSeries($view->series['Series_ID']);
        return $view;
    }

    /**
     * "Show series" page
     *
     * @return mixed
     */
    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if (null === $id) {
            return $this->forwardTo(__NAMESPACE__ . '\Series', 'list');
        }
        $view = $this->getViewModelWithSeries();
        if (!$view) {
            return $this->forwardTo(__NAMESPACE__ . '\Series', 'notfound');
        }
        $view->items = $this->getDbTable('itemsinseries')->getItemsForSeries($id);
        return $view;
    }

    /**
     * List series action
     *
     * @return mixed
     */
    public function listAction()
    {
        return $this->createViewModel(
            array(
                'series' => $this->getDbTable('series')->getList()
            )
        );
    }

    /**
     * Not found page
     *
     * @return mixed
     */
    public function notfoundAction()
    {
        return $this->createViewModel();
    }
}
