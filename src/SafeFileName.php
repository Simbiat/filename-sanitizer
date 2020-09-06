<?php
declare(strict_types=1);
namespace SafeFileName;

class SafeFileName
{
    private array $needles = [];
    private array $replaces = [
        #Remove control characters
        '/[[:cntrl:]]/i'=>'',
        #Replace characters with fullwidth alternatives
        '/</i'=>'＜',
        '/>/i'=>'＞',
        '/:/i'=>'：',
        '/"/i'=>'＂',
        '/\//i'=>'／',
        '/\\\\/i'=>'＼',
        '/\|/i'=>'｜',
        '/\?/i'=>'？',
        '/\*/i'=>'＊',
        #Replace Windows specific reserved words while retaining case
        '/^(CON)(\.*.*)$/'=>'ＣＯＮ$2',
        '/^(con)(\.*.*)$/'=>'ｃｏｎ$2',
        '/^(COn)(\.*.*)$/'=>'ＣＯｎ$2',
        '/^(CoN)(\.*.*)$/'=>'ＣｏＮ$2',
        '/^(Con)(\.*.*)$/'=>'Ｃｏｎ$2',
        '/^(cON)(\.*.*)$/'=>'ｃＯＮ$2',
        '/^(COn)(\.*.*)$/'=>'ＣＯｎ$2',
        '/^(coN)(\.*.*)$/'=>'ｃｏＮ$2',
        '/^(PRN)(\.*.*)$/'=>'ＰＲＮ$2',
        '/^(prn)(\.*.*)$/'=>'ｐｒｎ$2',
        '/^(PRn)(\.*.*)$/'=>'ＰＲｎ$2',
        '/^(PrN)(\.*.*)$/'=>'ＰｒＮ$2',
        '/^(Prn)(\.*.*)$/'=>'Ｐｒｎ$2',
        '/^(pRN)(\.*.*)$/'=>'ｐＲＮ$2',
        '/^(PRn)(\.*.*)$/'=>'ＰＲｎ$2',
        '/^(prN)(\.*.*)$/'=>'ｐｒＮ$2',
        '/^(AUX)(\.*.*)$/'=>'ＡＵＸ$2',
        '/^(aux)(\.*.*)$/'=>'ａｕｘ$2',
        '/^(AUx)(\.*.*)$/'=>'ＡＵｘ$2',
        '/^(AuX)(\.*.*)$/'=>'ＡｕＸ$2',
        '/^(Aux)(\.*.*)$/'=>'Ａｕｘ$2',
        '/^(aUX)(\.*.*)$/'=>'ａＵＸ$2',
        '/^(AUx)(\.*.*)$/'=>'ＡＵｘ$2',
        '/^(auX)(\.*.*)$/'=>'ａｕＸ$2',
        '/^(NUL)(\.*.*)$/'=>'ＮＵＬ$2',
        '/^(nul)(\.*.*)$/'=>'ｎｕｌ$2',
        '/^(NUl)(\.*.*)$/'=>'ＮＵｌ$2',
        '/^(NuL)(\.*.*)$/'=>'ＮｕＬ$2',
        '/^(Nul)(\.*.*)$/'=>'Ｎｕｌ$2',
        '/^(nUL)(\.*.*)$/'=>'ｎＵＬ$2',
        '/^(NUl)(\.*.*)$/'=>'ＮＵｌ$2',
        '/^(nuL)(\.*.*)$/'=>'ｎｕＬ$2',
        '/^(COM)(\d{1,})(\.*.*)$/'=>'ＣＯＭ$2$3',
        '/^(com)(\d{1,})(\.*.*)$/'=>'ｃｏｍ$2$3',
        '/^(COm)(\d{1,})(\.*.*)$/'=>'ＣＯｍ$2$3',
        '/^(CoM)(\d{1,})(\.*.*)$/'=>'ＣｏＭ$2$3',
        '/^(Com)(\d{1,})(\.*.*)$/'=>'Ｃｏｍ$2$3',
        '/^(cOM)(\d{1,})(\.*.*)$/'=>'ｃＯＭ$2$3',
        '/^(COm)(\d{1,})(\.*.*)$/'=>'ＣＯｍ$2$3',
        '/^(coM)(\d{1,})(\.*.*)$/'=>'ｃｏＭ$2$3',
        '/^(LPT)(\d{1,})(\.*.*)$/'=>'ＬＰＴ$2$3',
        '/^(lpt)(\d{1,})(\.*.*)$/'=>'ｌｐｔ$2$3',
        '/^(LPt)(\d{1,})(\.*.*)$/'=>'ＬＰｔ$2$3',
        '/^(LpT)(\d{1,})(\.*.*)$/'=>'ＬｐＴ$2$3',
        '/^(Lpt)(\d{1,})(\.*.*)$/'=>'Ｌｐｔ$2$3',
        '/^(lPT)(\d{1,})(\.*.*)$/'=>'ｌＰＴ$2$3',
        '/^(LPt)(\d{1,})(\.*.*)$/'=>'ＬＰｔ$2$3',
        '/^(lpT)(\d{1,})(\.*.*)$/'=>'ｌｐＴ$2$3',
    ];
    
    public function __construct()
    {
        #Setting needles (characters to search for) in order not to duplicate the values
        $this->needles = array_keys($this->replaces);
    }
    
    public function sanitize(string $string): string
    {
        #Replace special characters
        $string = preg_replace($this->needles, $this->replaces, $string);
        #Remove spaces and dots from right
        $string = rtrim(rtrim($string), '.');
        if $string === '' {
            throw new \UnexpectedValueException('Resulting string has no characters.');
        }
        return $string;
    }
}
?>