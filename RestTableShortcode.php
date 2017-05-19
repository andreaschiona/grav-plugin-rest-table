<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Grav\Common\Utils;
use Symfony\Component\Yaml\Yaml;
use RocketTheme\Toolbox\File\File;
// use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;
use League\Csv\Reader;
class RestTableShortcode extends Shortcode
{

    public function init()
    {
        $this->shortcode->getHandlers()->add('rest2table', array($this, 'process'));
    }
    public function process(ShortcodeInterface $sc) {
        $url = $sc->getParameter('url', null);

        //$raw = $sc->getParameter('raw', null);
        //if ($raw === null) {
        //    $raw = false;
        //} else {
        //    $raw = true;
        //}


        $class = $sc->getParameter('class', null);
        $caption = $sc->getParameter('caption', null);
        $header = $sc->getParameter('header', null);
        $headernames = $sc->getParameter('headernames', null);
        $fields = str_getcsv($sc->getParameter('fields', null));
        $list = $sc->getParameter('list', null);

        if ($header === null) {
            $header = true;
        } else {
            $header = false;
        }

        $curl = curl_init($url);

        // DEBUG FILE
        //$curl_log = fopen("/var/log/curl.log", 'a'); // open file for READ and write
        //file_put_contents("/var/log/curl_req.log", json_encode(($req)));

        curl_setopt_array($curl, array(
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            //CURLOPT_POSTFIELDS => json_encode($req),
            CURLOPT_VERBOSE => true
        ));

        $curl_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('status: ' . $status . 'error occured during curl exec. Additional info: ' . var_export($info));
        }
        curl_close($curl);

        // DEBUG FILE
        // file_put_contents("/var/log/curl_response.log", $curl_response);

        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }
        //echo 'response ok!';
        //var_export($decoded->response);


        // Load the data
        $data = json_decode($curl_response, true);

        // Build the table
        if ($data === null) {
            return "<p>Rest 2 Table: Something went wrong loading data from rest service '$url'.</p>";
        }
        $output = '';
        $id = $this->shortcode->getId($sc);
        $output .= '<table id="'.$id.'"';
        if ($class !== null) {
            $output .= ' class="'.htmlspecialchars($class).'"';
        }
        $output .= '>';
        // Insert caption if given
        if ( ($caption !== null) && (strlen($caption) > 0) ) {
            $output .= '<caption>'.htmlspecialchars($caption).'</caption>';
        }
        $rows = $list == NULL ? $data : $data[$list];

        if ($headernames != NULL) {
            $output .= '<thead><tr>';
            foreach (str_getcsv($headernames) as $cell) {
                $output .= '<th>'.$cell.'</th>';
            }
            $output .= '</tr></thead>';
        }


        $output .= '<tbody>';

        foreach ($rows as $row) {
            $output .= '<tr>';
            if ($fields != NULL) {
                $row = array_filter($row, function($k) use ($fields) {
                    return in_array($k, $fields);
                }, ARRAY_FILTER_USE_KEY);
            }
            foreach ($row as $cell) {
                $output .= '<td>'.htmlspecialchars($cell).'</td>';
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }
}
