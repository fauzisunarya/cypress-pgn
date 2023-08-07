<?php

namespace Drupal\restapi_telkom\Helper;

use Drupal;
use Drupal\file\Entity\File;

class Html_helper {

   public function catalogTemplate1(array $paket_list, array $settings, string $catalog_title)
   {
      $items = array_map(function($paket){
         return "<div class='pricing-type-1'>
           <div class='pricing-body'>
             <div class='pricing-title' style='height:31.5px;'>
               <div class='{$paket['promo_label']}'>{$paket['promo_text']}</div>
             </div>
             <div class='pricing-content'>
               <div class='pricing-speed'>
                 <img src='https://www.indihome.co.id/images/speed-gauge/speed-{$paket['link_image']}.svg' alt='speed'>
                 <div class='speed'>
                   <span>up to</span>
                   <h2 class='speed-label'>{$paket['speed']}</h2>
                   <span>Mbps</span>
                 </div>
               </div>
               <div class='pricing-description'>
                 <h6 class='title'>{$paket['title']}</h6>
                 <small class='sub-title'>{$paket['sub_title']}</small>
               </div>
             </div>
             <div class='price-cta'>
               <div class='price'>
                 <span class='currency'>Rp</span>
                   <h4 class='number'> {$paket['price']} </h4>
                 <span class='month'>/{$paket['billing_period']}</span>
               </div>
               <button class='btn-pricing-primary'>Berlangganan</button>
             </div>
           </div>
         </div>";
      }, $paket_list);
      return "<div style='display:flex;justify-content:space-around;flex-wrap:wrap;margin:20px 10px;'>".implode('', $items)."</div>";
   }

   public function catalogTemplate2(array $paket_list, array $settings, string $catalog_title)
   {
      $items = array_map(function($paket){
         return "<div class='pricing-type-2'>
           <div class='pricing-body'>
             <div class='pricing-title'>
               <div class='speed-title'>
                 <img src='https://www.indihome.co.id/images/ic-wifi-red.svg' alt='speed'>
                 <span>Speed</span>
               </div>
             </div>
             <div class='speed'>
               <h4 class='speed-label'>{$paket['speed']}</h4>
               <span>Mbps</span>
             </div>
             <div class='price'>
               <h4 class='number'>Rp {$paket['price']} </h4>
               <span class='month'>/{$paket['billing_period']}</span>
             </div>
             <div class='pricing-description'>
               <p>{$paket['title']}</p>
               <small>{$paket['sub_title']}</small>
             </div>
             <hr>
             <div class='benefit-title'>
               <img src='https://www.indihome.co.id/images/icon/icon-benefit.svg' alt='benefit'>
               <span>Benefit</span>
             </div>
             ". (!empty($paket['list_benefit']) ? "<div class='benefit-list'><ul>". implode('', $paket['list_benefit']) ."</ul></div>" : '') ."
             <button class='btn-pricing-primary'>Pilih Paket</button>
           </div>
         </div>";
      },$paket_list);
      return "<div style='display:flex;justify-content:space-around;flex-wrap:wrap;margin:20px 10px;'>".implode('', $items)."</div>";
   }

   public function catalogTemplate3(array $paket_list, array $settings, string $catalog_title)
   {
      $items = array_map(function($paket_val, $paket_id){
         $sequence = $paket_id+1; // $paket_id = zero indexed, sequence is indexed from 1
         $class_item = $sequence%3===2 ? 'red' : ($sequence%3===0 ? 'blue' : '');
         $class_btn  = $sequence%3===2 || $sequence%3===0 ? 'btn' : '';

         return "<div class='pricing-type-3 {$class_item}'>
           <div class='pricing-header'>
             <div class='speed'>
               <span>up to</span>
               <h1>{$paket_val['speed']}</h1>
               <span>Mbps</span>
             </div>
           </div>
           <div class='pricing-body'>
             <div class='price'>
               <span>Rp</span>
               <h1> {$paket_val['price']} </h1>
               <span>/{$paket_val['billing_period']}</span>
             </div>
             <div class='pricing-description'>
               <p class='fw-bold'>{$paket_val['title']}</p>
               <small>{$paket_val['sub_title']}</small>
             </div>
             ". (!empty($paket_val['list_benefit']) ? "<div class='benefit-list'><ul>". implode('', $paket_val['list_benefit']) ."</ul></div>" : '') ."
             <button class='{$class_btn} btn-success'>Berlangganan</button>
           </div>
         </div>";
      }, $paket_list, array_keys($paket_list));
      return "<div style='display:flex;justify-content:space-around;flex-wrap:wrap;margin:20px 10px;'>".implode('', $items)."</div>";
   }

   public function catalogTemplate4(array $paket_list, array $settings, string $catalog_title)
   {
      $table_header = '<tr>';
      foreach ($settings as $setting) :
        $title     = strtolower(str_replace(' ','-',$setting->label()));
        $logo_file = File::load($setting->field_temp_set_logo->getValue()[0]['target_id']);

        if ($logo_file) :
          $logo_uri = $logo_file->getFileUri();

          if (str_contains($logo_uri, 's3')) {
            $logo_url = "{$_ENV['APP_URL']}/restapi/v1/media_render/{$logo_file->uuid()}";
          }
          else{
            $logo_url = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::service('file_url_generator')->generateString($logo_uri);
          };
        endif;

        $table_header .= "
        <th>
          <img src='{$logo_url}' alt='logo-{$title}' style='max-width:". (strpos($title, 'indihome')!==false ? '70' : '50') ."px' width='100%'>
        </th>
        ";
      endforeach;
      $table_header .= "<th class='price'>Harga</th><tr>";

      // clear memory
      unset($title,$logo_file,$logo_url);

      $table_body = array_map(function($paket) use ($settings) {
         $text = '';
         foreach ($settings as $setting_id => $setting_val) {
            if ( strpos(strtolower($setting_val->label()), 'indihome')!==false ) {
               $text .= "<td class='speed'><h4>{$paket['speed']}</h4><span>Mbps</span></td>";
            }
            elseif (!empty($paket['list_benefit'][$setting_id])) {
               $text .= "<td>".strip_tags($paket['list_benefit'][$setting_id])."</td>";
            }
            else{
               $text .= "<td>-</td>";
            }
         };
         $text .= "
         <td>
             <div class='price'>
                 <span>Rp</span>
                 <h5> {$paket['price']} </h5>
                 <span>/bulan</span>
             </div>
         </td>
         ";
         return "<tr>$text</tr>";
      }, $paket_list);

      return "<div><div class='pricing-type-5' style='width:100%'><div class='pricing-body table-responsive'><table class='table'><thead>{$table_header}</thead><tbody>". implode('', $table_body) ."</tbody></table></div></div></div>";
   }

   public function catalogTemplate5(array $paket_list, array $settings, string $catalog_title)
   {
      $table_body = array_map(function($paket){
         return "<tr><td>{$paket['package_id']}</td><td>{$paket['title']}</td><td>{$paket['speed']} Mbps</td><td> {$paket['price']} </td></tr>";
      }, $paket_list);

      return "<div>
        <div class='pricing-type-4' style='width:100%'>
          <div class='pricing-body table-responsive'>
            <div class='pricing-title'>
              <div class='list-package-title'>
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='24' height='24'>
                  <path fill='none' d='M0 0h24v24H0z' />
                  <path
                    d='M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z'
                    fill='rgba(255,0,0,1)' />
                </svg>
                <h6>{$catalog_title}</h6>
              </div>
              <table class='table'>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Speed</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>". implode('', $table_body) ."</tbody>
              </table>
            </div>
          </div>
        </div>
      </div>";
   }

   public function catalogTemplate6(array $paket_list, array $settings, string $catalog_title)
   {
      $items = array_map(function($paket){

        $benefit = implode('', array_map(function($data){
            $value = !empty($data) ? $data : '-';
            return "<div class='content border-bottom'>{$value}</div>";
        }, $paket['list_benefit_non_formatted']));

        return "
            <div class='pricing-15-card'>
                <div class='title border-bottom'>
                    <span class='packet-type'>".$paket['title']."</span>
                    <h1>".$paket['speed']." gb</h1>
                </div>
                <div class='pricing-15-card-body'>
                    ".$benefit."
                    <div class='price'>
                        <span class='currency'>Rp</span>
                        <span class='nominal'>".$paket['price']."</span>
                        <span class='month'>/".$paket['billing_period']."</span>
                    </div>
                </div>
                <div class='pricing-15-card-footer'>
                    <button>Pilih Paket Ini</button>
                </div>
            </div>
        ";
      }, $paket_list);
      return "<div class='pricing-15 columns is-multiline is-centered'>".implode('', $items)."</div>";
   }

}