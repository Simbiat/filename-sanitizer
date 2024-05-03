<?php
declare(strict_types = 1);

namespace Simbiat;

/**
 * Replace restricted characters or combinations in filenames
 */
class SafeFileName
{
    private static array $replaces = [
        #Remove control characters and whitespaces except regular space
        '/[[:cntrl:]]/iu' => '',
        #Replace whitespace with regular space (hex 20)
        '/[\r\n\t\f\v\0\x{00A0}\x{2002}-\x{200B}\x{202F}\x{205F}\x{3000}\x{FEFF}]/iu' => ' ',
        #Replace characters with fullwidth alternatives
        '/</iu' => '＜',
        '/>/iu' => '＞',
        '/:/iu' => '：',
        '/"/iu' => '＂',
        '/\//iu' => '／',
        '/\\\\/iu' => '＼',
        '/\|/iu' => '｜',
        '/\?/iu' => '？',
        '/\*/iu' => '＊',
        #Replace Windows specific reserved words while retaining case
        '/^(CON)(\..*)?$/u' => 'ＣＯＮ$2',
        '/^(con)(\..*)?$/u' => 'ｃｏｎ$2',
        '/^(COn)(\..*)?$/u' => 'ＣＯｎ$2',
        '/^(CoN)(\..*)?$/u' => 'ＣｏＮ$2',
        '/^(Con)(\..*)?$/u' => 'Ｃｏｎ$2',
        '/^(cON)(\..*)?$/u' => 'ｃＯＮ$2',
        '/^(coN)(\..*)?$/u' => 'ｃｏＮ$2',
        '/^(PRN)(\..*)?$/u' => 'ＰＲＮ$2',
        '/^(prn)(\..*)?$/u' => 'ｐｒｎ$2',
        '/^(PRn)(\..*)?$/u' => 'ＰＲｎ$2',
        '/^(PrN)(\..*)?$/u' => 'ＰｒＮ$2',
        '/^(Prn)(\..*)?$/u' => 'Ｐｒｎ$2',
        '/^(pRN)(\..*)?$/u' => 'ｐＲＮ$2',
        '/^(prN)(\..*)?$/u' => 'ｐｒＮ$2',
        '/^(AUX)(\..*)?$/u' => 'ＡＵＸ$2',
        '/^(aux)(\..*)?$/u' => 'ａｕｘ$2',
        '/^(AUx)(\..*)?$/u' => 'ＡＵｘ$2',
        '/^(AuX)(\..*)?$/u' => 'ＡｕＸ$2',
        '/^(Aux)(\..*)?$/u' => 'Ａｕｘ$2',
        '/^(aUX)(\..*)?$/u' => 'ａＵＸ$2',
        '/^(auX)(\..*)?$/u' => 'ａｕＸ$2',
        '/^(NUL)(\..*)?$/u' => 'ＮＵＬ$2',
        '/^(nul)(\..*)?$/u' => 'ｎｕｌ$2',
        '/^(NUl)(\..*)?$/u' => 'ＮＵｌ$2',
        '/^(NuL)(\..*)?$/u' => 'ＮｕＬ$2',
        '/^(Nul)(\..*)?$/u' => 'Ｎｕｌ$2',
        '/^(nUL)(\..*)?$/u' => 'ｎＵＬ$2',
        '/^(nuL)(\..*)?$/u' => 'ｎｕＬ$2',
        '/^(COM)(\d)(\..*)?$/u' => 'ＣＯＭ$2$3',
        '/^(com)(\d)(\..*)?$/u' => 'ｃｏｍ$2$3',
        '/^(COm)(\d)(\..*)?$/u' => 'ＣＯｍ$2$3',
        '/^(CoM)(\d)(\..*)?$/u' => 'ＣｏＭ$2$3',
        '/^(Com)(\d)(\..*)?$/u' => 'Ｃｏｍ$2$3',
        '/^(cOM)(\d)(\..*)?$/u' => 'ｃＯＭ$2$3',
        '/^(coM)(\d)(\..*)?$/u' => 'ｃｏＭ$2$3',
        '/^(LPT)(\d)(\..*)?$/u' => 'ＬＰＴ$2$3',
        '/^(lpt)(\d)(\..*)?$/u' => 'ｌｐｔ$2$3',
        '/^(LPt)(\d)(\..*)?$/u' => 'ＬＰｔ$2$3',
        '/^(LpT)(\d)(\..*)?$/u' => 'ＬｐＴ$2$3',
        '/^(Lpt)(\d)(\..*)?$/u' => 'Ｌｐｔ$2$3',
        '/^(lPT)(\d)(\..*)?$/u' => 'ｌＰＴ$2$3',
        '/^(lpT)(\d)(\..*)?$/u' => 'ｌｐＴ$2$3',
    ];
    #Some more characters, that you might want to replace with fullwidth alternatives, depending on how you use the files
    private static array $replaces_ext = [
        '/\[/iu' => '［',
        '/\]/iu' => '］',
        '/=/iu' => '＝',
        '/;/iu' => '；',
        '/,/iu' => '，',
        '/&/iu' => '＆',
        '/\$/iu' => '＄',
        '/#/iu' => '＃',
        '/\(/iu' => '（',
        '/\)/iu' => '）',
        '/\~/iu' => '～',
        '/\`/iu' => '｀',
        '/\'/iu' => '＇',
        '/\!/iu' => '！',
        '/\{/iu' => '｛',
        '/\}/iu' => '｝',
        '/%/iu' => '％',
        '/\+/iu' => '＋',
        '/‘/iu' => '＇',
        '/’/iu' => '＇',
        '/«/iu' => '＂',
        '/»/iu' => '＂',
        '/”/iu' => '＂',
        '/“/iu' => '＂',
    ];
    
    /**
     * Replace restricted characters or combinations
     * @param string $string   String to sanitize
     * @param bool   $extended If `true` - replace some special characters (common for programming languages) with fullwidth alternatives, so that text will look similar, but will not work as actual code
     * @param bool   $remove   If `true` - replace matches with empty string, instead of safe alternatives
     *
     * @return string
     */
    public static function sanitize(string $string, bool $extended = true, bool $remove = false): string
    {
        #Replace special characters
        $string = preg_replace(array_keys(self::$replaces), ($remove ? '' : self::$replaces), $string);
        if ($extended) {
            $string = preg_replace(array_keys(self::$replaces_ext), ($remove ? '' : self::$replaces_ext), $string);
        }
        #Remove spaces and dots from right (spaces on the left are possible
        return rtrim(rtrim(rtrim($string), '.'));
    }
}
