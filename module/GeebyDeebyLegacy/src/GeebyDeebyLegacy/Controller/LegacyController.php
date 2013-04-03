<?php
/**
 * Legacy controller (for compatibility with old gamebooks.org filenames)
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
namespace GeebyDeebyLegacy\Controller;

/**
 * Legacy controller (for compatibility with old gamebooks.org filenames)
 *
 * @category GeebyDeeby
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/demiankatz/Geeby-Deeby Main Site
 */
class LegacyController extends \GeebyDeeby\Controller\AbstractBase
{
    /**
     * Legacy router action
     *
     * @return mixed
     */
    public function indexAction()
    {
        $path = explode('/', $this->params()->fromRoute('filename'));
        $file = array_pop($path);
        switch ($file) {
        case 'show_series.php':
            $this->redirect()->toRoute(
                'series', array('id' => $this->params()->fromQuery('id'))
            );
            break;
        }
        return $this->forwardTo(__NAMESPACE__ . '\Legacy', 'notfound');
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