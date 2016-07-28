<?php


abstract class MailTemplate
{

    // -- consts
    const OPEN_TAG = '-{';
    const CLOSE_TAG = '}-';
    // -- attributes
    private $plain_text = null; // string
    private $html = null; // string
    private $subject = null; // string
    private $attachments = null; // array(filepath, filename)

    // -- functions

    public function Substitute($dict)
    {
        foreach ($dict as $tag => $value) {
            $rtag = MailTemplate::OPEN_TAG . $tag . MailTemplate::CLOSE_TAG;
            if (isset($this->html)) {
                // on vérifie que le tag existe dans le texte template
                if (strpos($this->html, $rtag) === false) {
                    return false;
                }
                // si le tag existe on procède à la substitution
                $this->html = str_replace($rtag, $value, $this->html);
            }
            // on vérifie que le tag existe dans le texte template
            if (strpos($this->plain_text, $rtag) === false) {
                return false;
            }
            // si le tag existe on procède à la substitution
            $this->plain_text = str_replace($rtag, $value, $this->plain_text);
        }
        // on vérifie qu'au terme de la substitution il ne reste pas de -${TAG}$-
        if (strpos($this->html, MailTemplate::OPEN_TAG) !== false || strpos($this->plain_text, MailTemplate::OPEN_TAG) !== false) {
            return false;
        }
        return true;
    }

    public function HasHTMLBody()
    {
        return isset($this->html);
    }

    public function GetAttachments()
    {
        return $this->attachments;
    }

    public function GetSubject()
    {
        return $this->subject;
    }

    public function GetHTMLBody()
    {
        return $this->html;
    }

    public function GetPlainTextBody()
    {
        return $this->plain_text;
    }

# PROTECTED & PRIVATE ################################################

    protected function __construct($subject, $plain_text, $html = null, $attachments = array())
    {
        $this->subject = $subject;
        $this->plain_text = $plain_text;
        $this->html = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\"><html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head><body>" . $html . "</body></html>";;
        $this->attachments = $attachments;
    }

} 