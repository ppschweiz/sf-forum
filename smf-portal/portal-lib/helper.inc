<?php

function isAdminForum($topic)
{
  $adminboards = array(156);
  if (in_array($topic['board']['id'], $adminboards)) 
  {
    return "admin_forum";
  }
  return "";
}

function languageDomain($lang)
{
  switch($lang)
  {
    case "english":
      return "pirateparty.ch";
    case "english-utf8":
      return "pirateparty.ch";
    case "french-utf8":
      return "partipirate.ch";
    case "italian-utf8":
      return "partitopirata.ch";
    case "german_informal-utf8":
      return "piratenpartei.ch";
    default:
      return "piratenpartei.ch";
  }
}

function pps($lang)
{
  switch($lang)
  {
    case "english":
      return "Pirate Party Switzerland";
    case "english-utf8":
      return "Pirate Party Switzerland";
    case "french-utf8":
      return "Parti Pirate Suisse";
    case "italian-utf8":
      return "Partito Pirata Svizzero";
    case "german_informal-utf8":
      return "Piratenpartei Schweiz";
    default:
      return "Piratenpartei Schweiz";
  }
}

function portal_welcome($output_method = 'echo')
{
        global $context, $txt, $scripturl;

        if ($output_method == 'echo')
        {
                if ($context['user']['is_guest'])
                {
                    echo sprintf($txt['welcome_guest'], $txt['guest_title']);
                }
                else
                {
                    echo $txt['hello_member'], ' <strong>', $context['user']['name'], '</strong>';
                    if (allowedTo('pm_read'))
                    {
                        echo ', ',  $txt['msg_alert_you_have'] .' <a href="' . $scripturl . '?action=pm">' . $context['user']['messages'] . ' ' . 
                             ($context['user']['messages'] == '1' ? $txt['message_lowercase'] : $txt['msg_alert_messages']) . 
                             '</a>' . $txt['newmessages4'] . ' ';
                        if ($context['user']['unread_messages'] != '0') 
                        {
                            echo '<span class="newpm">';
                        }                            
                        echo $context['user']['unread_messages'] . ' ' . ($context['user']['unread_messages'] == '1' ? $txt['newmessages0'] : $txt['newmessages1']);
                        if ($context['user']['unread_messages'] != '0') 
                        {
                            echo '</span>';
                        }
                    }
                    echo '.';
                }
        }
        // Don't echo... then do what?!
        else
        {
                return $context['user'];
        }
}


function getTopicIcon($topic)
{
  switch($topic['board']['id'])
  {
    case 139:
       return '<div class="hicon"><img src="portal-icons/icon_bs.png" align="middle" alt="BS" />'.
              '<img src="portal-icons/icon_bl.png" align="middle" alt="BL" /></div>';
    case 140:
       return '<div class="hicon"><img src="portal-icons/icon_be.png" align="middle" alt="BE" /></div>';
    case 142:
       return '<div class="hicon"><img src="portal-icons/icon_lu.png" align="middle" alt="LU" />'.
              '<img src="portal-icons/icon_ow.png" align="middle" alt="OW" />'.
              '<img src="portal-icons/icon_nw.png" align="middle" alt="NW" />'.
              '<img src="portal-icons/icon_zg.png" align="middle" alt="ZG" />'.
              '<img src="portal-icons/icon_ur.png" align="middle" alt="UR" />'.
              '<img src="portal-icons/icon_sz.png" align="middle" alt="SZ" /></div>';
    case 143:
       return '<div class="hicon"><img src="portal-icons/icon_ag.png" align="middle" alt="AG" /></div>';
    case 144:
       return '<div class="hicon"><img src="portal-icons/icon_sg.png" align="middle" alt="SG" />'.
              '<img src="portal-icons/icon_ar.png" align="middle" alt="AR" />'.
              '<img src="portal-icons/icon_ai.png" align="middle" alt="AI" /></div>';
    case 145:
       return '<div class="hicon"><img src="portal-icons/icon_ju.png" align="middle" alt="JU" /></div>';
    case 146:
       return '<div class="hicon"><img src="portal-icons/icon_zh.png" align="middle" alt="ZH" /></div>';
    case 153:
    case 171:
    case 172:
       return '<div class="hicon"><img src="portal-icons/icon_tg.png" align="middle" alt="TG" />'.
              '<div class="hicon"><img src="portal-icons/icon_sh.png" align="middle" alt="SH" /></div>';
    case 167:
       return '<div class="hicon"><img src="portal-icons/icon_ge.png" align="middle" alt="GE" /></div>';
    case 168:
       return '<div class="hicon"><img src="portal-icons/icon_vd.png" align="middle" alt="VD" /></div>';
    case 169:
       return '<div class="hicon"><img src="portal-icons/icon_fr.png" align="middle" alt="FR" /></div>';
    case 173:
       return '<div class="hicon"><img src="portal-icons/icon_gl.png" align="middle" alt="GL" />'.
              '<div class="hicon"><img src="portal-icons/icon_gr.png" align="middle" alt="GR" /></div>';
    case 174:
       return '<div class="hicon"><img src="portal-icons/icon_vs.png" align="middle" alt="VS" /></div>';
    case 175:
       return '<div class="hicon"><img src="portal-icons/icon_ti.png" align="middle" alt="TI" /></div>';
    case 176:
       return '<div class="hicon"><img src="portal-icons/icon_so.png" align="middle" alt="SO" /></div>';
    case 185:
       return '<div class="hicon"><img src="portal-icons/icon_ne.png" align="middle" alt="NE" /></div>';

    case 182:
       return '<img src="portal-icons/pivote.png" align="middle" alt="pi-vote" />';
  }
  return $topic['icon'];
}

?>
