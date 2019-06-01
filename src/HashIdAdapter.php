<?php
namespace Mrlaozhou\HashId;
use Vinkla\Hashids\Facades\Hashids;

trait HashIdAdapter
{
    protected $hashId;

    /**
     * @return string
     */
    public function getHashId()
    {
        return Hashids::encode($this->getKey());
    }

    /**
     * 调用 $model->hash_id 时触发
     *
     * @return string
     */
    public function getHashIdAttribute()
    {
        if (!$this->hashId) {
            $this->hashId = $this->getHashId();
        }

        return $this->hashId;
    }

    /**
     * 先将参数 decode 为模型id，再调用父类的 resolveRouteBinding 方法
     *
     * @param $value
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function resolveRouteBinding($value)
    {
        if (!is_numeric($value)) {
            $value = current(Hashids::decode($value));
            if (!$value) {
                return null;
            }
        }
        return parent::resolveRouteBinding($value);
    }

    /**
     * 使用 hash_id 生成 URL
     *
     * @return string|null
     */
    public function getRouteKey()
    {
        return $this->getHashId();
    }
}