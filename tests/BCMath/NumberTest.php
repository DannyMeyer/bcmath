<?php

namespace Test\BCMath;

use BCMath\Format\Binary;
use BCMath\Format\Decimal;
use BCMath\Format\Format;
use BCMath\Format\Hexadecimal;
use BCMath\Number;
use PHPUnit\Framework\TestCase;

use function hash;

/**
 * Class NumberTest
 */
class NumberTest extends TestCase
{
    /** @var string[] */
    private $mappingGetValue = [
        'dec_string' => '10.41',
        'bin_string' => '-1011.101',
        'hex_string' => 'af10',
        'dec_object' => '9654.9456',
        'bin_object' => '1110.100001',
        'hex_object' => '-ff.fa1',
    ];

    /** @var string[] */
    private $mappingConvertToDecimal = [
        'dec_string' => '10.41',
        'bin_string' => '-11.00000000116415321826934814453125',
        'hex_string' => '44816',
        'dec_object' => '9654.9456',
        'bin_object' => '14.00000000768341124057769775390625',
        'hex_object' => '-255.976806640625',
    ];

    /** @var string[] */
    private $mappingConvertToBinary = [
        'dec_string' => '1010.011010001111010111000010100011110101110000101000111101011100001',
        'bin_string' => '-1011.101',
        'hex_string' => '1010111100010000',
        'dec_object' => '10010110110110.1111001000010010110101110111001100011000111111000101000001001',
        'bin_object' => '1110.100001',
        'hex_object' => '-11111111.00000000000000000000111110100001',
    ];

    /** @var string[] */
    private $mappingConvertToHexadecimal = [
        'dec_string' => 'a.68f5c28f5c28f5c2',
        'bin_string' => '-b.00000005',
        'hex_string' => 'af10',
        'dec_object' => '25b6.f212d77318fc5048',
        'bin_object' => 'e.00000021',
        'hex_object' => '-ff.fa1',
    ];

    /** @var string[] */
    private $mappingGetFormat = [
        'dec_string' => Format::FORMAT_DECIMAL,
        'bin_string' => Format::FORMAT_BINARY,
        'hex_string' => Format::FORMAT_HEXADECIMAL,
        'dec_object' => Format::FORMAT_DECIMAL,
        'bin_object' => Format::FORMAT_BINARY,
        'hex_object' => Format::FORMAT_HEXADECIMAL,
    ];

    /** @var string[] */
    private $mappingFloor = [
        'dec_string' => '10.4',
        'bin_string' => '-1100',
        'hex_string' => 'af10',
        'dec_object' => '9654.9',
        'bin_object' => '1110.1',
        'hex_object' => '-100',
    ];

    /** @var string[] */
    private $mappingCeil = [
        'dec_string' => '10.5',
        'bin_string' => '-1011.1',
        'hex_string' => 'af10',
        'dec_object' => '9655',
        'bin_object' => '1111',
        'hex_object' => '-ff.f',
    ];

    /** @var string[] */
    private $mappingRound = [
        'dec_string' => '10.41',
        'bin_string' => '-1011.10',
        'hex_string' => 'af10',
        'dec_object' => '9654.95',
        'bin_object' => '1110.10',
        'hex_object' => '-ff.fa',
    ];

    /** @var string[] */
    private $mappingAbs = [
        'dec_string' => '10.41',
        'bin_string' => '1011.101',
        'hex_string' => 'af10',
        'dec_object' => '9654.9456',
        'bin_object' => '1110.100001',
        'hex_object' => 'ff.fa1',
    ];

    /**
     * Provides Number objects with test data
     *
     * @return array
     */
    public function numberDataProvider(): array
    {
        return [
            ['dec_string', new Number('10.41', Format::FORMAT_DECIMAL)],
            ['bin_string', new Number('-1011.101', Format::FORMAT_BINARY)],
            ['hex_string', new Number('af10', Format::FORMAT_HEXADECIMAL)],
            ['dec_object', new Number(new Decimal('9654', '9456'))],
            ['bin_object', new Number(new Binary('1110', '100001'))],
            ['hex_object', new Number(new Hexadecimal('-ff', 'fa1'))],
        ];
    }

    /**
     * Test Number::getValue()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testGetValue(string $key, Number $number): void
    {
        $value = $number->getValue();
        $this->assertSame($this->mappingGetValue[$key], $value);
    }

    /**
     * Test Number::__toString()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function test__toString(string $key, Number $number): void
    {
        $value = (string)$number;
        $this->assertSame($this->mappingGetValue[$key], $value);
    }

    /**
     * Test Number::convertToDecimal()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testConvertToDecimal(string $key, Number $number): void
    {
        $number->convertToDecimal();
        $this->assertSame($this->mappingConvertToDecimal[$key], (string)$number);
    }

    /**
     * Test Number::convertToBinary()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testConvertToBinary(string $key, Number $number): void
    {
        $number->convertToBinary();
        $this->assertSame($this->mappingConvertToBinary[$key], (string)$number);
    }

    /**
     * Test Number::convertToHexadecimal()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testConvertToHexadecimal(string $key, Number $number): void
    {
        $number->convertToHexadecimal();
        $this->assertSame($this->mappingConvertToHexadecimal[$key], (string)$number);
    }

    /**
     * Test Number::convertToHexadecimal()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testGetFormat(string $key, Number $number): void
    {
        $this->assertSame($this->mappingGetFormat[$key], $number->getFormat());
    }

    /**
     * Test Number::floor()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testFloor(string $key, Number $number): void
    {
        $number->floor(1);
        $this->assertSame($this->mappingFloor[$key], (string)$number);
    }

    /**
     * Test Number::ceil()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testCeil(string $key, Number $number): void
    {
        $number->ceil(1);
        $this->assertSame($this->mappingCeil[$key], (string)$number);
    }

    /**
     * Test Number::round()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testRound(string $key, Number $number): void
    {
        $number->round(2);
        $this->assertSame($this->mappingRound[$key], (string)$number);
    }

    /**
     * Test Number::abs()
     *
     * @dataProvider numberDataProvider
     *
     * @param string $key
     * @param Number $number
     */
    public function testAbs(string $key, Number $number): void
    {
        $number->abs();
        $this->assertSame($this->mappingAbs[$key], (string)$number);
    }

    /**
     * Test convert of big numbers
     *
     * @return void
     */
    public function testBigNumbers(): void
    {
        $number = new Number(new Hexadecimal($this->generateBigNumber()));
        $expectedHexadecimal = 'a31bca02094eb78126a517b206a88c73cfa9ec6f704c7030d18212cace820f0'
            . '25f00bf0ea68dbf3f3a5436ca63b53bf7bf80ad8d5de7d8359d0b7fed9dbc3'
            . 'ab994dff4ea340f0a823f15d3f4f01ab62eae0e5da579ccb851f8db9dfe84c'
            . '58b2b37b89903a740e1ee172da793a6e79d560e5f7f9bd058a12a280433ed6'
            . 'fa46510a40b244112641dd78dd4f93b6c9190dd46e0099194d5a44257b7efa'
            . 'd6ef9ff4683da1eda0244448cb343aa688f5d3efd7314dafe580ac0bcbf115'
            . 'aeca9e8dc1143bafbf08882a2d10133093a1b8433f50563b93c14acd05b790'
            . '28eb1d12799027241450980651994501423a66c276ae26c43b739bc65c4e16'
            . 'b10c3af6c202aebba321d8b405e3ef2604959847b36d171eebebc4a8941dc7'
            . '0a4784935a4fca5d5813de84dfa049f06549aa61b20848c1633ce81b675286'
            . 'ea8fb53db240d831c56806df05371981a237d0ed11472fae7c94c9ac0eff1d'
            . '05413516710d17b10a4fb6f4517bda4a695f02d0a73dd4db543b4653df28f5'
            . 'd09dab86f92ffb9b86d01e25';

        $this->assertSame($expectedHexadecimal, (string)$number);

        $expectedBinary = '101000110001101111001010000000100000100101001110101101111000'
            . '000100100110101001010001011110110010000001101010100010001100'
            . '011100111100111110101001111011000110111101110000010011000111'
            . '000000110000110100011000001000010010110010101100111010000010'
            . '000011110000001001011111000000001011111100001110101001101000'
            . '110110111111001111110011101001010100001101101100101001100011'
            . '101101010011101111110111101111111000000010101101100011010101'
            . '110111100111110110000011010110011101000010110111111111101101'
            . '100111011011110000111010101110011001010011011111111101001110'
            . '101000110100000011110000101010000010001111110001010111010011'
            . '111101001111000000011010101101100010111010101110000011100101'
            . '110110100101011110011100110010111000010100011111100011011011'
            . '100111011111111010000100110001011000101100101011001101111011'
            . '100010011001000000111010011101000000111000011110111000010111'
            . '001011011010011110010011101001101110011110011101010101100000'
            . '111001011111011111111001101111010000010110001010000100101010'
            . '001010000000010000110011111011010110111110100100011001010001'
            . '000010100100000010110010010001000001000100100110010000011101'
            . '110101111000110111010100111110010011101101101100100100011001'
            . '000011011101010001101110000000001001100100011001010011010101'
            . '101001000100001001010111101101111110111110101101011011101111'
            . '100111111111010001101000001111011010000111101101101000000010'
            . '010001000100010010001100101100110100001110101010011010001000'
            . '111101011101001111101111110101110011000101001101101011111110'
            . '010110000000101011000000101111001011111100010001010110101110'
            . '110010101001111010001101110000010001010000111011101011111011'
            . '111100001000100010000010101000101101000100000001001100110000'
            . '100100111010000110111000010000110011111101010000010101100011'
            . '101110010011110000010100101011001101000001011011011110010000'
            . '001010001110101100011101000100100111100110010000001001110010'
            . '010000010100010100001001100000000110010100011001100101000101'
            . '000000010100001000111010011001101100001001110110101011100010'
            . '011011000100001110110111001110011011110001100101110001001110'
            . '000101101011000100001100001110101111011011000010000000101010'
            . '111010111011101000110010000111011000101101000000010111100011'
            . '111011110010011000000100100101011001100001000111101100110110'
            . '110100010111000111101110101111101011110001001010100010010100'
            . '000111011100011100001010010001111000010010010011010110100100'
            . '111111001010010111010101100000010011110111101000010011011111'
            . '101000000100100111110000011001010100100110101010011000011011'
            . '001000001000010010001100000101100011001111001110100000011011'
            . '011001110101001010000110111010101000111110110101001111011011'
            . '001001000000110110000011000111000101011010000000011011011111'
            . '000001010011011100011001100000011010001000110111110100001110'
            . '110100010001010001110010111110101110011111001001010011001001'
            . '101011000000111011111111000111010000010101000001001101010001'
            . '011001110001000011010001011110110001000010100100111110110110'
            . '111101000101000101111011110110100100101001101001010111110000'
            . '001011010000101001110011110111010100110110110101010000111011'
            . '010001100101001111011111001010001111010111010000100111011010'
            . '101110000110111110010010111111111011100110111000011011010000'
            . '0001111000100101';

        $number->convertToBinary();
        $this->assertSame($expectedBinary, (string)$number);

        $expectedDecimal = '59224776039897807010240090632480669509623314489390676626493688'
            . '73087940969852042043975439654329825916088669124919846331324117'
            . '46989485462542817084983968210258773173264260598924632564764118'
            . '76924737917074942979080922952746774586455764439507201951865164'
            . '66504537704766107513907055428847450769099770099214054481483828'
            . '83936121042865598264082929460011965054536527110308089055359844'
            . '01866943367377330410888363144946239746624435398178877224156284'
            . '13221910284861897914332945409616428791778986128064330712224459'
            . '44124281140680272637929843254815138333249825220925213404230390'
            . '33435835882935156236167083982025997489781921409041666899548094'
            . '59042142377675529643389236527025807678532106853015132204870786'
            . '17134432293561442357236442475956376647243397189366599030368154'
            . '68433094583343783702865117599971719121156122912419339809551265'
            . '95024111794759507480858406570530716595111707874626947760148588'
            . '1886452017503952384972868138007467658744956210977193336357';

        $number->convertToDecimal();
        $this->assertSame($expectedDecimal, (string)$number);

        $number->convertToHexadecimal();
        $this->assertSame($expectedHexadecimal, (string)$number);
    }

    /**
     * Generate a very long, hexadecimal number
     *
     * @return string
     */
    private function generateBigNumber(): string
    {
        // Start with a number to break with comfortable block sizes
        $number = 'a';

        for ($i = 0; $i <= 5; $i++) {
            $number .= hash('sha512', $i);
        }

        return $number;
    }
}
