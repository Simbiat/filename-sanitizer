<?php
declare(strict_types=1);
namespace Simbiat;

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
        '/^(COM)(\d{1,})(\.*.*)$/u' => 'ＣＯＭ$2$3',
        '/^(com)(\d{1,})(\.*.*)$/u' => 'ｃｏｍ$2$3',
        '/^(COm)(\d{1,})(\.*.*)$/u' => 'ＣＯｍ$2$3',
        '/^(CoM)(\d{1,})(\.*.*)$/u' => 'ＣｏＭ$2$3',
        '/^(Com)(\d{1,})(\.*.*)$/u' => 'Ｃｏｍ$2$3',
        '/^(cOM)(\d{1,})(\.*.*)$/u' => 'ｃＯＭ$2$3',
        '/^(coM)(\d{1,})(\.*.*)$/u' => 'ｃｏＭ$2$3',
        '/^(LPT)(\d{1,})(\.*.*)$/u' => 'ＬＰＴ$2$3',
        '/^(lpt)(\d{1,})(\.*.*)$/u' => 'ｌｐｔ$2$3',
        '/^(LPt)(\d{1,})(\.*.*)$/u' => 'ＬＰｔ$2$3',
        '/^(LpT)(\d{1,})(\.*.*)$/u' => 'ＬｐＴ$2$3',
        '/^(Lpt)(\d{1,})(\.*.*)$/u' => 'Ｌｐｔ$2$3',
        '/^(lPT)(\d{1,})(\.*.*)$/u' => 'ｌＰＴ$2$3',
        '/^(lpT)(\d{1,})(\.*.*)$/u' => 'ｌｐＴ$2$3',
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
