<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use think\route\Rule;

class StrategyCommand extends Command
{
    protected function configure()
    {
        /*
         php think cxj:strategy --method initCompanyPolicy
         */
        // 指令配置
        $this->setName('Strategy')
//            ->addOption('method')
            ->addOption('method', null, Option::VALUE_REQUIRED, 'method name')
            ->setDescription('the Strategy : policy | initCompanyPolicy | test ');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!$input->hasOption('method')) {
            // 指令输出
            $output->writeln('not find methods');
            die;
        }

        $method = $input->getOption('method');

        $methodArr = [
            'policy',
            'test',
            'initCompanyPolicy',
        ];
        if (!in_array($method, $methodArr)) {
            // 指令输出
            $output->error('error director');
            die;
        }
        $this->$method($input, $output);
        // 指令输出
        $output->writeln('ok');
    }

    public function initCompanyPolicy()
    {
        //
        Db::table('company')->where([
            'is_deleted' => 0
        ])->chunk(100, function($companys) {
            foreach ($companys as $company) {
                // 处理结果集...

                $ret = $this->initOnePolicy($company['id']);
                if (true !== $ret) {
                    $this->output->error("init_policy_error:company_id_[{$company['id']}],msg:" . $ret);
                }
            }
        });
        $this->output->info('success');
    }

    private function initOnePolicy($companyId)
    {
        try {

            $count = Db::table('strategy_policy')
                ->where('company_id', $companyId)
                ->count();
            if ($count) {
                return true;
            }
            $policy = [
                1 => '环境方针',
                2 => '职业健康安全方针',
                3 => '能源方针',
            ];

            $insertArr = [];
            foreach ($policy as $k => $v) {
                $insertArr[] = [
                    'company_id' => $companyId,
                    'type'       => $k,
                    'content'    => '',
                ];
            }
            Db::table('strategy_policy')->insertAll($insertArr);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;

    }

    public function policy($input, Output $output)
    {
        $output->info('policy==');
    }

    public function test()
    {
        $this->output->info('test');
    }
}
